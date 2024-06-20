<?php

declare(strict_types=1);

namespace App\Handler;

use App\ActorSystem\AppActor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class CancelEventHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CancelEventHandler
    {
        return new CancelEventHandler($container->get(AppActor::class));
    }
}
