<?php

declare(strict_types=1);

namespace App\ActorSystem;

use Laminas\ServiceManager\ServiceManager;
use Mezzio\Swoole\Event\WorkerStartEvent;
use Phluxor\ActorSystem;
use Phluxor\ActorSystem\Props;
use Psr\Container\ContainerInterface;
use RuntimeException;

readonly class BootAppActor
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function __invoke(WorkerStartEvent $event): void
    {
        $system  = ActorSystem::create();
        $spawned = $system->root()->spawnNamed(
            Props::fromProducer(fn() => new BoxOffice()),
            AppActor::NAME
        );
        if ($this->container instanceof ServiceManager) {
            $this->container->setService(AppActor::class, new AppActor($system->root(), $spawned->getRef()));
            return;
        }
        throw new RuntimeException('Container is not a ServiceManager');
    }
}
