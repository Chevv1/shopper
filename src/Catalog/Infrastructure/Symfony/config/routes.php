<?php

declare(strict_types=1);

use App\Catalog\Presentation\Controller\CategoryController;
use App\Catalog\Presentation\Controller\ProductController;
use Symfony\Component\Routing\Loader\Configurator\Routes;
use Symfony\Component\Routing\Requirement\Requirement;

return Routes::config(
    config: [
        'products_list' => [
            'path' => '/api/v1/products',
            'controller' => [ProductController::class, 'listProducts'],
            'methods' => ['GET'],
        ],
        'product_show' => [
            'path' => '/api/v1/products/{id}',
            'controller' => [ProductController::class, 'showProduct'],
            'requirements' => [
                'id' => Requirement::UUID,
            ],
            'methods' => ['GET'],
        ],
        'categories_list' => [
            'path' => '/api/v1/categories',
            'controller' => [CategoryController::class, 'listCategories'],
            'methods' => ['GET'],
        ],
    ],
);
