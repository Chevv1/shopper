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
        namespace: 'App\\Catalog\\Application\\',
        resource: '../../src/Catalog/Application/',
    );

    $services->load(
        namespace: 'App\\Catalog\\Infrastructure\\',
        resource: '../../src/Catalog/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Catalog\\Presentation\\',
        resource: '../../src/Catalog/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\Catalog\Application\Repository\ProductRepositoryInterface::class,
        referencedId: \App\Catalog\Infrastructure\Repository\Read\DoctrineProductRepository::class
    );

    $services->alias(
        id: \App\Catalog\Application\Repository\CategoryRepositoryInterface::class,
        referencedId: \App\Catalog\Infrastructure\Repository\Read\DoctrineCategoryRepository::class
    );
};
