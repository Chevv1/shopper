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
        namespace: 'App\\Payment\\Application\\',
        resource: '../../src/Payment/Application/',
    );

    $services->load(
        namespace: 'App\\Payment\\Infrastructure\\',
        resource: '../../src/Payment/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Payment\\Presentation\\',
        resource: '../../src/Payment/Presentation/',
    );

    // Read Repositories
    $services->alias(
        id: \App\Payment\Application\Repository\PaymentMethodRepositoryInterface::class,
        referencedId: \App\Payment\Infrastructure\Repository\Read\DoctrinePaymentMethodRepository::class
    );
};
