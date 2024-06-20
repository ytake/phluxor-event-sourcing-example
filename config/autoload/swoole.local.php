<?php

declare(strict_types=1);

use Mezzio\Swoole\ConfigProvider;
use Mezzio\Swoole\Event\RequestEvent;
use Mezzio\Swoole\Event\RequestHandlerRequestListener;
use Mezzio\Swoole\Event\ServerShutdownEvent;
use Mezzio\Swoole\Event\ServerShutdownListener;
use Mezzio\Swoole\Event\ServerStartEvent;
use Mezzio\Swoole\Event\ServerStartListener;
use Mezzio\Swoole\Event\StaticResourceRequestListener;
use Mezzio\Swoole\Event\WorkerStartEvent;
use Mezzio\Swoole\Event\WorkerStartListener;

return array_merge((new ConfigProvider())(), [
    'mezzio-swoole' => [
        'swoole-http-server' => [
            'host'             => 'insert hostname to use here',
            'port'             => 8080, // use an integer value here
            'enable_coroutine' => true,
            'listeners'        => [
                ServerStartEvent::class    => [
                    ServerStartListener::class,
                ],
                WorkerStartEvent::class    => [
                    WorkerStartListener::class,
                    App\ActorSystem\BootAppActor::class, // boot the actor system
                ],
                RequestEvent::class        => [
                    StaticResourceRequestListener::class,
                    RequestHandlerRequestListener::class,
                ],
                ServerShutdownEvent::class => [
                    ServerShutdownListener::class,
                ],
            ],
        ],
    ],
]);
