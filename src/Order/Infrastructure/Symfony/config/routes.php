<?php

declare(strict_types=1);

use App\Order\Presentation\Controller\OrderController;
use Symfony\Component\Routing\Loader\Configurator\Routes;
use Symfony\Component\Routing\Requirement\Requirement;

return Routes::config(
    config: [
        'show_my_orders' => [
            'path' => '/api/v1/orders',
            'controller' => [OrderController::class, 'showMyOrders'],
            'methods' => ['GET'],
        ],
        'show_order' => [
            'path' => '/api/v1/orders/{orderId}',
            'controller' => [OrderController::class, 'showOrder'],
            'requirements' => [
                'orderId' => Requirement::UUID,
            ],
            'methods' => ['GET'],
        ],
        'place_order' => [
            'path' => '/api/v1/orders',
            'controller' => [OrderController::class, 'placeOrder'],
            'methods' => ['POST'],
        ],
    ],
);
