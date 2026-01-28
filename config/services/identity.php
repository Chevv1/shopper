<?php

declare(strict_types=1);

use App\Identity\Application\Command\RegisterUserIfNotExists\RegisterUserIfNotExistsCommand;
use App\Identity\Application\Command\RegisterUserIfNotExists\RegisterUserIfNotExistsCommandHandler;
use App\Identity\Application\Query\GetUserForToken\GetUserForTokenQuery;
use App\Identity\Application\Query\GetUserForToken\GetUserForTokenQueryHandler;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use App\Identity\Domain\Service\PasswordHasherInterface;
use App\Identity\Domain\Service\TokenGeneratorInterface;
use App\Identity\Infrastructure\Service\SymfonyPasswordHasher;
use App\Identity\Infrastructure\Service\FirebaseJwtTokenGenerator;
use App\Identity\Infrastructure\Security\JwtAuthenticator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
        namespace: 'App\\Identity\\Application\\',
        resource: '../../src/Identity/Application/',
    );

    $services->load(
        namespace: 'App\\Identity\\Infrastructure\\',
        resource: '../../src/Identity/Infrastructure/',
    );

    $services->load(
        namespace: 'App\\Identity\\Presentation\\',
        resource: '../../src/Identity/Presentation/',
    );

    // Domain services
    $services->set(PasswordHasherInterface::class, SymfonyPasswordHasher::class)
        ->arg(0, service('security.user_password_hasher'));

    $services->set(TokenGeneratorInterface::class, FirebaseJwtTokenGenerator::class)
        ->args([
            param('jwt.secret_key'),
            param('jwt.refresh_secret_key'),
            param('jwt.access_token_ttl'),
            param('jwt.refresh_token_ttl'),
        ]);

    // Security
    $services->set(JwtAuthenticator::class)
        ->arg(key: '$secretKey', value: param('jwt.secret_key'));

    // Read Repositories
    $services->alias(
        id: \App\Identity\Application\Repository\UserRepositoryInterface::class,
        referencedId: \App\Identity\Infrastructure\Repository\Read\DoctrineUserRepository::class
    );

    $services->alias(
        id: \App\Identity\Application\Repository\ProfileRepositoryInterface::class,
        referencedId: \App\Identity\Infrastructure\Repository\Read\DoctrineProfileRepository::class
    );

    // Write Repositories
    $services->alias(
        id: \App\Identity\Domain\Repository\UserRepositoryInterface::class,
        referencedId: \App\Identity\Infrastructure\Repository\Write\DoctrineUserRepository::class
    );

    $services->alias(
        id: \App\Identity\Domain\Repository\ProfileRepositoryInterface::class,
        referencedId: \App\Identity\Infrastructure\Repository\Write\DoctrineProfileRepository::class,
    );

    // Handlers
    $services
        ->set(id: RegisterUserIfNotExistsCommandHandler::class)
        ->tag(
            name: 'messenger.message_handler',
            attributes: [
                'bus' => 'messenger.bus.command',
                'handles' => RegisterUserIfNotExistsCommand::class,
            ]
        );

    $services
        ->set(id: GetUserForTokenQueryHandler::class)
        ->tag(
            name: 'messenger.message_handler',
            attributes: [
                'bus' => 'messenger.bus.command',
                'handles' => GetUserForTokenQuery::class,
            ],
        );
};
