<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use App\Event\EventCreated;
use App\Message\EventDescription;
use App\Message\EventExists;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class CreateEventHandler implements RequestHandlerInterface
{
    public function __construct(
        private AppActor $appActor
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = $this->appActor;
        $post  = $request->getParsedBody();
        if (! isset($post['tickets'])) {
            return new JsonResponse(['error' => 'tickets is required'], 400);
        }
        $eventName = $request->getAttribute('name');
        $future    = $actor->root->requestFuture(
            $actor->actorRef,
            new EventDescription($eventName, (int) $post['tickets']),
            2000
        );
        $fr        = $future->result();
        if ($fr->error() !== null) {
            return new JsonResponse(['error' => $fr->error()], 400);
        }
        $v = $fr->value();
        return match (true) {
            $v instanceof EventCreated => new JsonResponse(['message' => 'event created', 'event' => $eventName]),
            $v instanceof EventExists  => new JsonResponse(['message' => 'event exists', 'event' => $eventName], 409),
            default                   => new JsonResponse(['message' => 'unknown'], 400),
        };
    }
}
