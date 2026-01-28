<?php

declare(strict_types=1);

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
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

    $services->load(
        namespace: 'App\\VendorManagement\\Application\\',
        resource: '../../src/VendorManagement/Application/',
    );

    $services->load(
        namespace: 'App\\VendorManagement\\Infrastructure\\',
        resource: '../../src/VendorManagement/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\VendorManagement\\Presentation\\',
        resource: '../../src/VendorManagement/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\VendorManagement\Domain\Repository\ProductRepositoryInterface::class,
        referencedId: \App\VendorManagement\Infrastructure\Repository\Read\DoctrineProductRepository::class
    );

    // Write Repositories
    $services->alias(
        id: \App\VendorManagement\Domain\Repository\ProductRepositoryInterface::class,
        referencedId: \App\VendorManagement\Infrastructure\Repository\Write\DoctrineProductRepository::class
    );
};
