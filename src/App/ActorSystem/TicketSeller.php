<?php

declare(strict_types=1);

namespace App\ActorSystem;

use App\Event\EventCreated;
use App\Message\Add;
use App\Message\Cancel;
use App\Message\Event;
use App\Message\GetEvent;
use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;

class TicketSeller implements ActorInterface
{
    private int $tickets = 0;
    private string $name = '';
    private string $id = '';

    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        switch (true) {
            case $msg instanceof Add:
                // actorの状態を変更します
                $this->name = $msg->name;
                $this->tickets = $msg->tickets;
                $this->id = (string) $context->self();
                $context->send($msg->replyTo, new EventCreated($msg->name, $msg->tickets));
                break;
            case $msg instanceof GetEvent:
                $context->requestWithCustomSender(
                    $context->sender(),
                    new Event($this->name, $this->tickets),
                    $context->parent()
                );
                break;
            case $msg instanceof Cancel:
                // チケット販売自体をキャンセルする例
                // チケット販売状態管理を行っているアクターを停止する
                $context->requestWithCustomSender(
                    $context->sender(),
                    new Cancel(),
                    $context->parent()
                );
                $context->poison($context->self());
                break;
        }
    }
}
