<?php

declare(strict_types=1);

namespace App\VendorManagement\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductUnitRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $content,

        #[Assert\All([
            new Assert\Uuid,
        ])]
        public ?array $assetIds,
    ) {}
}
