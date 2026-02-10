<?php

declare(strict_types=1);

use App\Files\Application\Service\ImageServiceInterface;
use App\Files\Domain\Repository\ImageRepositoryInterface;
use App\Files\Infrastructure\Repository\Write\DoctrineImageRepository;
use App\Files\Infrastructure\Service\LocalImageService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return App::config(
    config: [
        'services' => [
            'App\\Files\\' => [
                'resource' => '../../../',
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

            ImageServiceInterface::class => [
                'class' => LocalImageService::class,
                'arguments' => [
                    '$uploadDirectory' => param('files_directory'),
                    '$publicPath' => param('files_base_url'),
                ],
            ],

            // Domain Repositories
            ImageRepositoryInterface::class => [
                'class' => DoctrineImageRepository::class,
            ],
        ],
    ],
);
