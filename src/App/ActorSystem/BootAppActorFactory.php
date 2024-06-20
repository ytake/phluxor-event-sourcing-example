<?php

declare(strict_types=1);

namespace App\ActorSystem;

use Psr\Container\ContainerInterface;

class BootAppActorFactory
{
    public function __invoke(ContainerInterface $container): BootAppActor
    {
        return new BootAppActor($container);
    }
}
