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
        namespace: 'App\\Chat\\Application\\',
        resource: '../../src/Chat/Application/',
    );

    $services->load(
        namespace: 'App\\Chat\\Infrastructure\\',
        resource: '../../src/Chat/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Chat\\Presentation\\',
        resource: '../../src/Chat/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\Chat\Application\Repository\ChatRepositoryInterface::class,
        referencedId: \App\Chat\Infrastructure\Repository\Read\DoctrineChatRepository::class
    );

    // Write Repositories
    $services->alias(
        id: \App\Chat\Domain\Repository\ChatRepositoryInterface::class,
        referencedId: \App\Chat\Infrastructure\Repository\Write\DoctrineChatRepository::class
    );
};
