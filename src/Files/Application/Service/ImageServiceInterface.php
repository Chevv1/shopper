<?php

declare(strict_types=1);

namespace App\Files\Application\Service;

use App\Files\Domain\Entity\ImageFile;

interface ImageServiceInterface
{
    public function upload(
        string $tmpName,
        string $name,
        string $type,
        int $size,
    ): ImageFile;

    public function delete(ImageFile $image): void;
}
