<?php

declare(strict_types=1);

use App\Chat\Application\Repository\ChatRepositoryInterface as ApplicationChatRepositoryInterface;
use App\Chat\Domain\Repository\ChatRepositoryInterface as DomainChatRepository;
use App\Chat\Infrastructure\Repository\Read\DoctrineChatRepository as ReadDoctrineChatRepositoryInterface;
use App\Chat\Infrastructure\Repository\Write\DoctrineChatRepository as WriteDoctrineChatRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;

return App::config(
    config: [
        'services' => [
            'App\\Chat\\' => [
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
            ApplicationChatRepositoryInterface::class => [
                'class' => ReadDoctrineChatRepositoryInterface::class,
            ],

            // Domain Repositories
            DomainChatRepository::class => [
                'class' => WriteDoctrineChatRepository::class,
            ],
        ],
    ],
);
