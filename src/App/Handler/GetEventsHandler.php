<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use App\Message\GetEvents;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class GetEventsHandler implements RequestHandlerInterface
{
    public function __construct(
        private AppActor $appActor
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor  = $this->appActor;
        $future = $actor->root->requestFuture($actor->actorRef, new GetEvents(), 2000);
        $fr     = $future->result();
        if ($fr->error() !== null) {
            return new JsonResponse(['error' => $fr->error()], 400);
        }
        return new JsonResponse($fr->value());
    }
}
