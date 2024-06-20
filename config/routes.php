<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    // Register the AppActor in the container
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
    $app->get('/events', App\Handler\GetEventsHandler::class, 'api.events');
    $app->get('/events/{name}', App\Handler\GetEventHandler::class, 'api.event');
    $app->post('/events/{name}', App\Handler\CreateEventHandler::class, 'api.create-event');
    $app->delete('/events/{name}', App\Handler\CancelEventHandler::class, 'api.cancel-event');
};
