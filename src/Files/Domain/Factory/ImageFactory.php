<?php

declare(strict_types=1);

namespace App\Files\Domain\Factory;

use App\Files\Domain\Entity\Image;
use App\Files\Domain\Entity\ImageFile;
use App\Files\Domain\Entity\ImageId;
use App\Files\Domain\Entity\ImageOwnerId;
use App\Files\Domain\Entity\ImageUploadedAt;

final readonly class ImageFactory
{
    public static function create(
        ImageFile $file,
        ImageOwnerId $ownerId
    ): Image {
        return new Image(
            id: ImageId::generate(),
            file: $file,
            ownerId: $ownerId,
            uploadedAt: ImageUploadedAt::now(),
        );
    }
}
