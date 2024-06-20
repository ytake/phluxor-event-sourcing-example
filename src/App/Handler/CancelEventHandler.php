<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use App\Message\Cancel;
use App\Message\CancelEvent;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class CancelEventHandler implements RequestHandlerInterface
{
    public function __construct(
        private AppActor $appActor
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor     = $this->appActor;
        $eventName = $request->getAttribute('name');
        $future    = $actor->root->requestFuture(
            $actor->actorRef,
            new CancelEvent($eventName),
            2000
        );
        $fr        = $future->result();
        if ($fr->error() !== null) {
            return new JsonResponse(['error' => $fr->error()], 400);
        }
        $v = $fr->value();
        return match (true) {
            $v instanceof Cancel => new JsonResponse(['message' => 'cancelled'], 200),
            default => new JsonResponse(['message' => 'unknown'], 400),
        };
    }
}
