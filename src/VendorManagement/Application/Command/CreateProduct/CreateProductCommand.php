<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Command\CreateProduct;

use App\Shared\Application\Command\CommandInterface;

final readonly class CreateProductCommand implements CommandInterface
{
    public function __construct(
        public string $title,
        public string $description,
        public int    $price,
        public array  $imageIds,
        public string $userId,
        public string $categoryId,
    ) {}
}
