<?php

declare(strict_types=1);

use App\Payment\Presentation\Controller\PaymentMethodController;
use Symfony\Component\Routing\Loader\Configurator\Routes;

return Routes::config(
    config: [
        'get_payment_methods' => [
            'path' => '/api/v1/payments/methods',
            'controller' => [PaymentMethodController::class, 'listPaymentMethods'],
            'methods' => ['GET'],
        ],
        'get_payment_url' => [
            'path' => '/api/v1/payments',
            'controller' => [PaymentMethodController::class, 'createPaymentUrl'],
            'methods' => ['POST'],
        ],
        'handle_webhook' => [
            'path' => '/api/v1/payments/webhook',
            'controller' => [PaymentMethodController::class, 'handleWebhook'],
            'methods' => ['POST'],
        ],
    ],
);
