<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class GetEventsHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): GetEventsHandler
    {
        return new GetEventsHandler($container->get(AppActor::class));
    }
}
