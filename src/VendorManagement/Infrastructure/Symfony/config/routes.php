<?php

declare(strict_types=1);

use App\VendorManagement\Presentation\Controller\ProductController;
use Symfony\Component\Routing\Loader\Configurator\Routes;
use Symfony\Component\Routing\Requirement\Requirement;

return Routes::config(
    config: [
        'create_product' => [
            'path' => '/api/v1/products',
            'controller' => [ProductController::class, 'createProduct'],
            'methods' => ['POST'],
        ],
        'create_product_unit' => [
            'path' => '/api/v1/products/{productId}/units',
            'controller' => [ProductController::class, 'createProductUnit'],
            'requirements' => [
                'productId' => Requirement::UUID,
            ],
            'methods' => ['POST'],
        ],
        'my_products' => [
            'path' => '/api/v1/products/my',
            'controller' => [ProductController::class, 'getMyProducts'],
            'methods' => ['GET'],
        ],
    ],
);
