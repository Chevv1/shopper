<?php

declare(strict_types=1);

use App\Catalog\Application\Repository\CategoryRepositoryInterface;
use App\Catalog\Application\Repository\ProductRepositoryInterface;
use App\Catalog\Infrastructure\Repository\Read\DoctrineCategoryRepository;
use App\Catalog\Infrastructure\Repository\Read\DoctrineProductRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

return App::config(
    config: [
        'services' => [
            'App\\Catalog\\' => [
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
            ProductRepositoryInterface::class => [
                'class' => DoctrineProductRepository::class,
            ],
            CategoryRepositoryInterface::class => [
                'class' => DoctrineCategoryRepository::class,
            ],
        ],
    ],
);
