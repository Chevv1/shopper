<?php

declare(strict_types=1);

use App\Files\Application\Service\ImageServiceInterface;
use App\Files\Infrastructure\Service\LocalImageService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

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
        namespace: 'App\\Files\\Application\\',
        resource: '../../src/Files/Application/',
    );

    $services->load(
        namespace: 'App\\Files\\Infrastructure\\',
        resource: '../../src/Files/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Files\\Presentation\\',
        resource: '../../src/Files/Presentation/',
    );

    $services
        ->set(id: ImageServiceInterface::class, class: LocalImageService::class)
        ->args([
            '$uploadDirectory' => param('files_directory'),
            '$publicPath' => param('files_base_url'),
        ]);

    // Write Repositories
    $services->alias(
        id: \App\Files\Domain\Repository\ImageRepositoryInterface::class,
        referencedId: \App\Files\Infrastructure\Repository\Write\DoctrineImageRepository::class
    );
};
