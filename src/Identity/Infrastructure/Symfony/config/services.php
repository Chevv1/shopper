<?php

declare(strict_types=1);

use App\Identity\Application\Repository\ProfileRepositoryInterface as ApplicationProfileRepositoryInterface;
use App\Identity\Application\Repository\UserRepositoryInterface as ApplicationUserRepositoryInterface;
use App\Identity\Domain\Repository\ProfileRepositoryInterface as DomainProfileRepositoryInterface;
use App\Identity\Domain\Repository\UserRepositoryInterface as DomainUserRepositoryInterface;
use App\Identity\Infrastructure\Repository\Read\DoctrineProfileRepository as ReadDoctrineProfileRepository;
use App\Identity\Infrastructure\Repository\Read\DoctrineUserRepository as ReadDoctrineUserRepository;
use App\Identity\Infrastructure\Repository\Write\DoctrineProfileRepository as WriteDoctrineProfileRepository;
use App\Identity\Infrastructure\Repository\Write\DoctrineUserRepository as WriteDoctrineUserRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;
use App\Identity\Domain\Service\PasswordHasherInterface;
use App\Identity\Domain\Service\TokenGeneratorInterface;
use App\Identity\Infrastructure\Service\SymfonyPasswordHasher;
use App\Identity\Infrastructure\Service\FirebaseJwtTokenGenerator;
use App\Identity\Infrastructure\Security\JwtAuthenticator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return App::config(
    config: [
        'services' => [
            'App\\Identity\\' => [
                'resource' => '../../..',
                'exclude' => '../../Symfony',
            ],
            '_instanceof' => [
                CommandHandlerInterface::class => [
                    'tags' => [
                        [
                            'messenger.message_handler' => [
                                'attributes' => [
                                    'bus' => 'messenger.bus.command',
                                ],
                            ],
                        ],
                    ],
                ],
                QueryHandlerInterface::class => [
                    'tags' => [
                        [
                            'messenger.message_handler' => [
                                'attributes' => [
                                    'bus' => 'messenger.bus.query',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            JwtAuthenticator::class => [
                'arguments' => [
                    '$secretKey' => param('jwt.secret_key'),
                ],
            ],
            TokenGeneratorInterface::class => [
                'class' => FirebaseJwtTokenGenerator::class,
                'arguments' => [
                    param('jwt.secret_key'),
                    param('jwt.refresh_secret_key'),
                    param('jwt.access_token_ttl'),
                    param('jwt.refresh_token_ttl'),
                ],
            ],
            PasswordHasherInterface::class => [
                'class' => SymfonyPasswordHasher::class,
                'arguments' => [
                    service('security.user_password_hasher'),
                ],
            ],

            // Application Repositories
            ApplicationUserRepositoryInterface::class => [
                'class' => ReadDoctrineUserRepository::class,
            ],
            ApplicationProfileRepositoryInterface::class => [
                'class' => ReadDoctrineProfileRepository::class,
            ],

            // Domain Repositories
            DomainUserRepositoryInterface::class => [
                'class' => WriteDoctrineUserRepository::class,
            ],
            DomainProfileRepositoryInterface::class => [
                'class' => WriteDoctrineProfileRepository::class,
            ],
        ],
    ],
);
