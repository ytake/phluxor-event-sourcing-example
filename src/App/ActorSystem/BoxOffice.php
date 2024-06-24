<?php

declare(strict_types=1);

namespace App\ActorSystem;

use App\Message\Add;
use App\Message\Cancel;
use App\Message\CancelEvent;
use App\Message\Event;
use App\Message\EventDescription;
use App\Message\EventExists;
use App\Message\EventNotFound;
use App\Message\GetEvent;
use App\Message\GetEvents;
use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Exception\NameExistsException;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Props;
use Swoole\Coroutine\WaitGroup;

use function array_merge;
use function sprintf;

class BoxOffice implements ActorInterface
{
    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        switch (true) {
            case $msg instanceof EventDescription:
                $result = $context->spawnNamed(
                    Props::fromProducer(fn() => new TicketSeller()),
                    $msg->name
                );
                if ($result->isError() instanceof NameExistsException) {
                    // アクターが生成済みの場合は生成済みであることを通知します
                    $context->respond(new EventExists());
                    break;
                }
                $context->send($result->getRef(), new Add($msg->name, $msg->tickets, $context->sender()));
                break;
            case $msg instanceof GetEvents:
                $context->send($context->sender(), $this->fetchEvents($context));
                break;
            case $msg instanceof GetEvent:
                // box_officeで受け取ったメッセージをticket_sellerに転送し、
                // 送信先をエンドポイントに対応しているアクターに戻すよう指示している
                $match = false;
                foreach ($context->children() as $child) {
                    if ((string)$child === sprintf("%s/%s", $context->self(), $msg->name)) {
                        $match = true;
                        $context->requestWithCustomSender($child, new GetEvent(), $context->sender());
                    }
                }
                if (!$match) {
                    $context->respond(new EventNotFound());
                }
                break;
            case $msg instanceof CancelEvent:
                foreach ($context->children() as $child) {
                    if (
                        (string)$child === sprintf("%s/%s", $context->self(), $msg->name)
                    ) {
                        $context->requestWithCustomSender($child, new Cancel(), $context->sender());
                        return;
                    }
                }
                $context->respond(new EventNotFound());
        }
    }

    private function fetchEvents(ContextInterface $context): array
    {
        $wg = new WaitGroup();
        $events = [];
        foreach ($context->children() as $child) {
            $wg->add();
            $future = $context->requestFuture($child, new GetEvent(), 2000);
            $fr = $future->result();
            if ($fr->error() !== null) {
                $wg->done();
                continue;
            }
            if ($fr->value() instanceof Event) {
                $wg->done();
                $events = array_merge($events, [$fr->value()]);
            }
        }
        $wg->wait();
        return $events;
    }
}
