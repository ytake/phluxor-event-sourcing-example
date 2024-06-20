<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class CreateEventHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CreateEventHandler
    {
        return new CreateEventHandler($container->get(AppActor::class));
    }
}
