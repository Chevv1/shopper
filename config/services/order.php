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
        namespace: 'App\\Order\\Application\\',
        resource: '../../src/Order/Application/',
    );

    $services->load(
        namespace: 'App\\Order\\Infrastructure\\',
        resource: '../../src/Order/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Order\\Presentation\\',
        resource: '../../src/Order/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\Order\Application\Repository\OrderRepositoryInterface::class,
        referencedId: \App\Order\Infrastructure\Repository\Read\DoctrineOrderRepository::class
    );

    // Write Repositories
    $services->alias(
        id: \App\Order\Domain\Repository\OrderRepositoryInterface::class,
        referencedId: \App\Order\Infrastructure\Repository\Write\DoctrineOrderRepository::class
    );

    $services->alias(
        id: \App\Order\Domain\Repository\CheckoutRepositoryInterface::class,
        referencedId: \App\Order\Infrastructure\Repository\Write\DoctrineCheckoutRepository::class
    );

    // ACL
    $services->alias(
        id: \App\Order\Application\Port\Catalog\CatalogServiceInterface::class,
        referencedId: \App\Order\Infrastructure\ACL\CatalogService::class,
    );

    $services->alias(
        id: \App\Order\Application\Port\Cart\CartServiceInterface::class,
        referencedId: \App\Order\Infrastructure\ACL\CartService::class,
    );
};
