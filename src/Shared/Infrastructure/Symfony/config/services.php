<?php

declare(strict_types=1);

use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use App\Shared\Application\LoggerInterface;
use App\Shared\Infrastructure\Bus\SymfonyEventBus;
use App\Shared\Infrastructure\Bus\SymfonyCommandBus;
use App\Shared\Infrastructure\Bus\SymfonyQueryBus;
use App\Shared\Infrastructure\PsrLogger;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return App::config(
    config: [
        'services' => [
            'App\\Shared\\' => [
                'resource' => '../../..',
                'exclude' => '../../Symfony',
            ],
            CommandBusInterface::class => [
                'class' => SymfonyCommandBus::class,
                'arguments' => [
                    '$bus' => service(serviceId: 'messenger.bus.command'),
                ],
            ],
            QueryBusInterface::class => [
                'class' => SymfonyQueryBus::class,
                'arguments' => [
                    '$bus' => service(serviceId: 'messenger.bus.query'),
                ],
            ],
            EventBusInterface::class => [
                'class' => SymfonyEventBus::class,
                'arguments' => [
                    '$bus' => service(serviceId: 'messenger.bus.event'),
                ],
            ],
            LoggerInterface::class => [
                'class' => PsrLogger::class,
            ],
        ],
    ],
);
