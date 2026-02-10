<?php

declare(strict_types=1);

use App\Identity\Presentation\Controller\AuthController;
use App\Identity\Presentation\Controller\ProfileController;
use Symfony\Component\Routing\Loader\Configurator\Routes;

return Routes::config(
    config: [
        'login' => [
            'path' => '/api/v1/auth/login',
            'controller' => [AuthController::class, 'login'],
            'methods' => ['POST']
        ],
        'refresh_token' => [
            'path' => '/api/v1/auth/refresh',
            'controller' => [AuthController::class, 'refreshToken'],
            'methods' => ['POST']
        ],
        'show_my_profile' => [
            'path' => '/api/v1/profile',
            'controller' => [ProfileController::class, 'showMyProfile'],
            'methods' => ['GET']
        ],
        'create_profile_for_current_user' => [
            'path' => '/api/v1/profile',
            'controller' => [ProfileController::class, 'createProfileForCurrentUser'],
            'methods' => ['POST']
        ],
    ],
);
