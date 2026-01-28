<?php

declare(strict_types=1);

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->instanceof(fqcn: CommandHandlerInterface::class)
        ->tag(
            name: 'messenger.message_handler',
            attributes: ['bus' => 'messenger.bus.command'],
        );

    $services
        ->instanceof(fqcn: QueryHandlerInterface::class)
        ->tag(
            name: 'messenger.message_handler',
            attributes: ['bus' => 'messenger.bus.query'],
        );

    $services
        ->instanceof(fqcn: EventHandlerInterface::class)
        ->tag(
            name: 'messenger.message_handler',
            attributes: ['bus' => 'messenger.bus.event'],
        );

    $services->load(
        namespace: 'App\\Cart\\Application\\',
        resource: '../../src/Cart/Application/',
    );

    $services->load(
        namespace: 'App\\Cart\\Infrastructure\\',
        resource: '../../src/Cart/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Cart\\Presentation\\',
        resource: '../../src/Cart/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\Cart\Application\Repository\CartRepositoryInterface::class,
        referencedId: \App\Cart\Infrastructure\Repository\Read\DoctrineCartRepository::class
    );

    // Write Repositories
    $services->alias(
        id: \App\Cart\Domain\Repository\CartRepositoryInterface::class,
        referencedId: \App\Cart\Infrastructure\Repository\Write\DoctrineCartRepository::class
    );

    // ACL
    $services->alias(
        id: \App\Cart\Application\Port\CatalogServiceInterface::class,
        referencedId: \App\Cart\Infrastructure\ACL\CatalogService::class
    );
};
