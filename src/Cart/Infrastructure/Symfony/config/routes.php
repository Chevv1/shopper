<?php

declare(strict_types=1);

use App\Cart\Presentation\Controller\CartController;
use Symfony\Component\Routing\Loader\Configurator\Routes;
use Symfony\Component\Routing\Requirement\Requirement;

return Routes::config(
    config: [
        'show_cart' => [
            'path' => '/api/v1/cart',
            'controller' => [CartController::class, 'showCart'],
            'methods' => ['GET'],
        ],
        'clearCart' => [
            'path' => '/api/v1/cart',
            'controller' => [CartController::class, 'clearCart'],
            'methods' => ['DELETE'],
        ],
        'add_to_cart' => [
            'path' => '/api/v1/cart/items',
            'controller' => [CartController::class, 'addToCart'],
            'methods' => ['POST'],
        ],
        'update_cart_item' => [
            'path' => '/api/v1/cart/items/{productId}',
            'controller' => [CartController::class, 'updateCartItem'],
            'requirements' => [
                'productId' => Requirement::UUID,
            ],
            'methods' => ['PATCH'],
        ],
        'delete_cart_item' => [
            'path' => '/api/v1/cart/items/{productId}',
            'controller' => [CartController::class, 'deleteCartItem'],
            'requirements' => [
                'productId' => Requirement::UUID,
            ],
            'methods' => ['DELETE'],
        ],
    ],
);
