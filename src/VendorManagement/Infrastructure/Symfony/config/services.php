<?php

declare(strict_types=1);

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\VendorManagement\Application\Repository\ProductRepositoryInterface as ApplicationProductRepositoryInterface;
use App\VendorManagement\Domain\Repository\ProductRepositoryInterface as DomainProductRepositoryInterface;
use App\VendorManagement\Infrastructure\Repository\Read\DoctrineProductRepository as ReadDoctrineProductRepository;
use App\VendorManagement\Infrastructure\Repository\Write\DoctrineProductRepository as WriteDoctrineProductRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

return App::config(
    config: [
        'services' => [
            'App\\VendorManagement\\' => [
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
            ApplicationProductRepositoryInterface::class => [
                'class' => ReadDoctrineProductRepository::class,
            ],

            // Domain Repositories
            DomainProductRepositoryInterface::class => [
                'class' => WriteDoctrineProductRepository::class,
            ],
        ],
    ],
);
