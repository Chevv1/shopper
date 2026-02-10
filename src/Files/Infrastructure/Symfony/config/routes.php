<?php

declare(strict_types=1);

use App\Files\Presentation\Controller\FileController;
use Symfony\Component\Routing\Loader\Configurator\Routes;

return Routes::config(
    config: [
        'upload_image' => [
            'path' => '/api/v1/files/images/upload',
            'controller' => [FileController::class, 'uploadImage'],
            'methods' => ['POST'],
        ],
    ],
);
