<?php

declare(strict_types=1);

use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Infrastructure\Bus\SymfonyEventBus;
use App\Shared\Infrastructure\Bus\SymfonyCommandBus;
use App\Shared\Infrastructure\Bus\SymfonyQueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->load(
            namespace: 'App\\Shared\\Application\\',
            resource: '../../src/Shared/Application/',
        )
        ->exclude(excludes: '../../src/Shared/Application/Bus/');

    $services
        ->load(
            namespace: 'App\\Shared\\Infrastructure\\',
            resource: '../../src/Shared/Infrastructure/',
        );

    $services
        ->instanceof(CommandHandlerInterface::class)
        ->tag(name: 'messenger.message_handler', attributes: ['bus' => 'messenger.bus.command']);

    $services
        ->instanceof(QueryHandlerInterface::class)
        ->tag(name: 'messenger.message_handler', attributes: ['bus' => 'messenger.bus.query']);

    $services
        ->instanceof(EventHandlerInterface::class)
        ->tag(name: 'messenger.message_handler', attributes: ['bus' => 'messenger.bus.event']);

    $services
        ->set(CommandBusInterface::class, SymfonyCommandBus::class)
        ->arg(key: '$bus', value: service(serviceId: 'messenger.bus.command'));

    $services
        ->set(QueryBusInterface::class, SymfonyQueryBus::class)
        ->arg(key: '$bus', value: service(serviceId: 'messenger.bus.query'));

    $services
        ->set(EventBusInterface::class, SymfonyEventBus::class)
        ->arg(key: '$bus', value: service(serviceId: 'messenger.bus.event'));
};
