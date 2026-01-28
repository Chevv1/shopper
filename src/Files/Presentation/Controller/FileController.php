<?php

declare(strict_types=1);

namespace App\Files\Presentation\Controller;

use App\Files\Application\Command\UploadImage\UploadImageCommand;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;

final class FileController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function uploadImage(
        #[MapUploadedFile] UploadedFile $file,
    ): JsonResponse {
        $result = $this->commandBus->dispatch(
            command: new UploadImageCommand(
                tmpName: $file->getPathname(),
                name: $file->getClientOriginalName(),
                type: $file->getMimeType(),
                size: $file->getSize(),
                ownerUserId: $this->getUser()->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: [
                'success' => true,
                'data' => [
                    'file_id' => $result->entityId,
                ],
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
