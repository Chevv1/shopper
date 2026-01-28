<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Command\CreateProductUnit;

use App\Shared\Application\Command\CommandInterface;

final readonly class CreateProductUnitCommand implements CommandInterface
{
    public function __construct(
        public string $userId,
        public string $productId,
        public string $content,
        public array $assetIds,
    ) {}
}
