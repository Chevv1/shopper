<?php

declare(strict_types=1);

namespace App\Files\Application\Command\UploadImage;

use App\Files\Application\Service\ImageServiceInterface;
use App\Files\Domain\Entity\Image;
use App\Files\Domain\Entity\ImageOwnerId;
use App\Files\Domain\Factory\ImageFactory;
use App\Files\Domain\Repository\ImageRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class UploadImageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ImageServiceInterface $imageUploadService,
        private ImageRepositoryInterface $imageRepository,
    ) {}

    public function __invoke(UploadImageCommand $command): CommandResult
    {
        $imageFile = $this->imageUploadService->upload(
            tmpName: $command->tmpName,
            name: $command->name,
            type: $command->type,
            size: $command->size,
        );

        $image = ImageFactory::create(
            file: $imageFile,
            ownerId: new ImageOwnerId($command->ownerUserId),
        );

        $this->imageRepository->save($image);

        return CommandResult::success(
            entityId: $image->id(),
        );
    }
}
