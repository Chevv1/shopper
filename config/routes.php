<?php

declare(strict_types=1);

use App\Cart\Presentation\Controller\CartController;
use App\Catalog\Presentation\Controller\CategoryController;
use App\Catalog\Presentation\Controller\ProductController;
use App\Chat\Presentation\Controller\ChatController;
use App\Files\Presentation\Controller\FileController;
use App\Identity\Presentation\Controller\AuthController;
use App\Identity\Presentation\Controller\ProfileController;
use App\Order\Presentation\Controller\OrderController;
use App\Payment\Presentation\Controller\PaymentMethodController;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Requirement\Requirement;

return function (RoutingConfigurator $routes): void {
    // Swagger
    $routes
        ->add('swagger_ui', '/api/v1/docs')
        ->controller([TemplateController::class, 'templateAction'])
        ->defaults(['template' => 'swagger/index.html.twig']);

    // Identity module
    $routes
        ->add(name: 'login', path: '/api/v1/auth/login')
        ->controller([AuthController::class, 'login'])
        ->methods(['POST']);

    $routes
        ->add(name: 'refresh_token', path: '/api/v1/auth/refresh')
        ->controller([AuthController::class, 'refreshToken'])
        ->methods(['POST']);

    $routes
        ->add(name: 'show_my_profile', path: '/api/v1/profile')
        ->controller([ProfileController::class, 'showMyProfile'])
        ->methods(['GET']);

    $routes
        ->add(name: 'create_profile_for_current_user', path: '/api/v1/profile')
        ->controller([ProfileController::class, 'createProfileForCurrentUser'])
        ->methods(['POST']);

    // Catalog module
    $routes
        ->add(name: 'products_list', path: '/api/v1/products')
        ->controller([ProductController::class, 'listProducts'])
        ->methods(['GET']);

    $routes
        ->add(name: 'product_show', path: '/api/v1/products/{id}')
        ->controller([ProductController::class, 'showProduct'])
        ->requirements(['id' => Requirement::UUID])
        ->methods(['GET']);

    $routes
        ->add(name: 'categories_list', path: '/api/v1/categories')
        ->controller([CategoryController::class, 'listCategories'])
        ->methods(['GET']);

    // Cart module
    $routes
        ->add(name: 'show_cart', path: '/api/v1/cart')
        ->controller([CartController::class, 'showCart'])
        ->methods(['GET']);

    $routes
        ->add(name: 'clearCart', path: '/api/v1/cart')
        ->controller([CartController::class, 'clearCart'])
        ->methods(['DELETE']);

    $routes
        ->add(name: 'add_to_cart', path: '/api/v1/cart/items')
        ->controller([CartController::class, 'addToCart'])
        ->methods(['POST']);

    $routes
        ->add(name: 'update_cart_item', path: '/api/v1/cart/items/{productId}')
        ->controller([CartController::class, 'updateCartItem'])
        ->methods(['PATCH']);

    $routes
        ->add(name: 'remove_cart_item', path: '/api/v1/cart/items/{productId}')
        ->controller([CartController::class, 'deleteCartItem'])
        ->methods(['DELETE']);

    // Orders module
    $routes
        ->add(name: 'show_my_orders', path: '/api/v1/orders')
        ->controller([OrderController::class, 'showMyOrders'])
        ->methods(['GET']);

    $routes
        ->add(name: 'place_order', path: '/api/v1/orders')
        ->controller([OrderController::class, 'placeOrder'])
        ->methods(['POST']);

    // Files module
    $routes
        ->add(name: 'upload_image', path: '/api/v1/files/images/upload')
        ->controller([FileController::class, 'uploadImage'])
        ->methods(['POST']);

    // Vendor management module
    $routes
        ->add(name: 'create_product', path: '/api/v1/products')
        ->controller([\App\VendorManagement\Presentation\Controller\ProductController::class, 'createProduct'])
        ->methods(['POST']);

    $routes
        ->add(name: 'create_product_unit', path: '/api/v1/products/{productId}/units')
        ->controller([\App\VendorManagement\Presentation\Controller\ProductController::class, 'createProductUnit'])
        ->requirements(['productId' => Requirement::UUID])
        ->methods(['POST']);

    // Payment module
    $routes
        ->add(name: 'get_payment_methods', path: '/api/v1/payment_methods')
        ->controller([PaymentMethodController::class, 'listPaymentMethods'])
        ->methods(['GET']);

    // Chat module
    $routes
        ->add(name: 'get_chats', path: '/api/v1/chats')
        ->controller([ChatController::class, 'getChats'])
        ->methods(['GET']);

    $routes
        ->add(name: 'send_message_to_chat', path: '/api/v1/chats/{chatId}/messages')
        ->controller([ChatController::class, 'sendMessage'])
        ->methods(['POST']);
};
