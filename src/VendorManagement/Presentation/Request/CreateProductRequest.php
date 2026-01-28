<?php

declare(strict_types=1);

namespace App\VendorManagement\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,

        #[Assert\NotBlank]
        public string $description,

        #[Assert\NotBlank]
        public int  $price,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $categoryId,

        #[Assert\All([
            new Assert\Uuid,
        ])]
        #[Assert\NotBlank]
        public array  $imageIds,
    ) {}
}
