<?php

declare(strict_types=1);

use App\Chat\Presentation\Controller\ChatController;
use Symfony\Component\Routing\Loader\Configurator\Routes;
use Symfony\Component\Routing\Requirement\Requirement;

return Routes::config(
    config: [
        'get_chats' => [
            'path' => '/api/v1/chats',
            'controller' => [ChatController::class, 'getChats'],
            'methods' => ['GET'],
        ],
        'send_message_to_chat' => [
            'path' => '/api/v1/chats/{chatId}/messages',
            'controller' => [ChatController::class, 'sendMessage'],
            'requirements' => [
                'chatId' => Requirement::UUID,
            ],
            'methods' => ['POST'],
        ],
    ],
);
