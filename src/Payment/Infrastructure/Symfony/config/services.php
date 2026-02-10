<?php

declare(strict_types=1);

use App\Payment\Application\Repository as ApplicationRepository;
use App\Payment\Application\Service\Order\OrderServiceInterface;
use App\Payment\Domain\Repository as DomainRepository;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use App\Payment\Infrastructure\ACL\OrderService;
use App\Payment\Infrastructure\Gateway\CompositePaymentGateway;
use App\Payment\Infrastructure\Gateway\PaymentMethodResolver;
use App\Payment\Infrastructure\Gateway\Provider\DummyPaymentGateway;
use App\Payment\Infrastructure\Repository\Read as ReadRepository;
use App\Payment\Infrastructure\Repository\Write as WriteRepository;
use App\Payment\Infrastructure\Security\IsSignedWebhook;
use App\Payment\Infrastructure\Security\WebhookSignatureSubscriber;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\App;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return App::config(
    config: [
        'services' => [
            'App\\Payment\\' => [
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
            ApplicationRepository\PaymentMethodRepositoryInterface::class => [
                'class' => ReadRepository\DoctrinePaymentMethodRepository::class,
            ],

            // Domain Repositories
            DomainRepository\PaymentRepositoryInterface::class => [
                'class' => WriteRepository\DoctrinePaymentRepository::class,
            ],

            DomainRepository\PaymentMethodRepositoryInterface::class => [
                'class' => WriteRepository\DoctrinePaymentMethodRepository::class
            ],

            // ACL
            OrderServiceInterface::class => [
                'class' => OrderService::class,
            ],

            DummyPaymentGateway::class => [
                'arguments' => [
                    '$gatewayUrl' => env('DUMMY_PAYMENT_GATEWAY_URL'),
                ],
            ],

            PaymentGatewayInterface::class => [
                'class' => CompositePaymentGateway::class,
                'arguments' => [
                    '$gateways' => [
                        'dummy' => service(DummyPaymentGateway::class),
                    ],
                ],
            ],

            PaymentMethodResolver::class => [
                'arguments' => [
                    '$methodToProviderMap' => [
                        'crypto' => 'dummy',
                    ],
                ],
            ],

            WebhookSignatureSubscriber::class => [
                'arguments' => [
                    '$webhookSecret' => env('WEBHOOK_PAYMENT_SECRET_KEY'),
                ],
            ],
        ],
    ],
);
