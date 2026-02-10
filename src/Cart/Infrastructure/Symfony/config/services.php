<?php

declare(strict_types=1);

use App\Cart\Application\Port\CatalogServiceInterface;
use App\Cart\Application\Repository\CartRepositoryInterface as ApplicationCartRepositoryInterface;
use App\Cart\Domain\Repository\CartRepositoryInterface as DomainCartRepositoryInterface;
use App\Cart\Infrastructure\ACL\CatalogService;
use App\Cart\Infrastructure\Repository\Read\DoctrineCartRepository as ReadDoctrineCartRepository;
use App\Cart\Infrastructure\Repository\Write\DoctrineCartRepository as WriteDoctrineCartRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

return App::config(
    config: [
        'services' => [
            'App\\Cart\\' => [
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
                EventHandlerInterface::class => [
                    'tags' => [
                        [
                            'messenger.message_handler' => [
                                'attributes' => [
                                    'bus' => 'messenger.bus.event',
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // Application Repositories
            ApplicationCartRepositoryInterface::class => [
                'class' => ReadDoctrineCartRepository::class,
            ],

            // Domain Repositories
            DomainCartRepositoryInterface::class => [
                'class' => WriteDoctrineCartRepository::class,
            ],

            // ACL
            CatalogServiceInterface::class => [
                'class' => CatalogService::class,
            ],
        ],
    ],
);
