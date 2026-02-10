<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\Routing\Loader\Configurator\Routes;

return Routes::config(
    config: [
        'swagger_ui' => [
            'path' => '/api/v1/docs',
            'controller' => [TemplateController::class, 'templateAction'],
            'defaults' => [
                'template' => 'swagger/index.html.twig',
            ],
            'methods' => ['GET'],
        ],
        'identity' => [
            'resource' => '../src/Identity/Infrastructure/Symfony/config/routes.php',
        ],
        'catalog' => [
            'resource' => '../src/Catalog/Infrastructure/Symfony/config/routes.php',
        ],
        'cart' => [
            'resource' => '../src/Cart/Infrastructure/Symfony/config/routes.php',
        ],
        'orders' => [
            'resource' => '../src/Order/Infrastructure/Symfony/config/routes.php',
        ],
        'files' => [
            'resource' => '../src/Files/Infrastructure/Symfony/config/routes.php',
        ],
        'vendor_management' => [
            'resource' => '../src/VendorManagement/Infrastructure/Symfony/config/routes.php',
        ],
        'payment' => [
            'resource' => '../src/Payment/Infrastructure/Symfony/config/routes.php',
        ],
        'chat' => [
            'resource' => '../src/Chat/Infrastructure/Symfony/config/routes.php',
        ],
    ],
);
