<?php

declare(strict_types=1);

namespace App\Files\Presentation\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UploadImageRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\File(
            maxSize: '15M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF, or WebP)'
        )]
        public UploadedFile $file,
    ) {}
}
