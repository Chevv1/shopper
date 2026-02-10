<?php

declare(strict_types=1);

use App\Order\Application\Repository\OrderRepositoryInterface as ApplicationOrderRepositoryInterface;
use App\Order\Application\Service\Cart\CartServiceInterface;
use App\Order\Application\Service\Catalog\CatalogServiceInterface;
use App\Order\Domain\Repository\OrderRepositoryInterface as DomainOrderRepositoryInterface;
use App\Order\Infrastructure\ACL\CartService;
use App\Order\Infrastructure\ACL\CatalogService;
use App\Order\Infrastructure\EventPublisher\DomainEventPublisher;
use App\Order\Infrastructure\Repository\Read\DoctrineOrderRepository as ReadDoctrineOrderRepository;
use App\Order\Infrastructure\Repository\Write\DoctrineOrderRepository as WriteDoctrineOrderRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

return App::config(
    config: [
        'services' => [
            'App\\Order\\' => [
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
            ApplicationOrderRepositoryInterface::class => [
                'class' => ReadDoctrineOrderRepository::class,
            ],

            // Domain Repositories
            DomainOrderRepositoryInterface::class => [
                'class' => WriteDoctrineOrderRepository::class,
            ],

            // ACL
            CatalogServiceInterface::class => [
                'class' => CatalogService::class,
            ],

            CartServiceInterface::class => [
                'class' => CartService::class,
            ],
        ],
    ],
);
