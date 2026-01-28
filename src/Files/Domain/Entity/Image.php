<?php

declare(strict_types=1);

namespace App\Files\Domain\Entity;

final readonly class Image
{
    public function __construct(
        private ImageId         $id,
        private ImageFile       $file,
        private ImageOwnerId    $ownerId,
        private ImageUploadedAt $uploadedAt,
    ) {}

    public function id(): ImageId
    {
        return $this->id;
    }

    public function file(): ImageFile
    {
        return $this->file;
    }

    public function ownerId(): ImageOwnerId
    {
        return $this->ownerId;
    }

    public function uploadedAt(): ImageUploadedAt
    {
        return $this->uploadedAt;
    }
}
