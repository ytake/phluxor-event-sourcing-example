<?php

declare(strict_types=1);

namespace App;

use App\Handler\CancelEventHandler;
use App\Handler\CancelEventHandlerFactory;
use App\Handler\CreateEventHandler;
use App\Handler\CreateEventHandlerFactory;
use App\Handler\GetEventHandler;
use App\Handler\GetEventHandlerFactory;
use App\Handler\GetEventsHandler;
use App\Handler\GetEventsHandlerFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class     => Handler\PingHandler::class,
                Handler\HomePageHandler::class => Handler\HomePageHandler::class,
            ],
            'factories'  => [
                CreateEventHandler::class => CreateEventHandlerFactory::class,
                GetEventsHandler::class   => GetEventsHandlerFactory::class,
                GetEventHandler::class    => GetEventHandlerFactory::class,
                CancelEventHandler::class => CancelEventHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
