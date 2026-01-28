<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product\ProductUnit;

use App\Shared\Domain\ValueObject\ValueObject;

final readonly class ProductUnitAsset extends ValueObject
{
    public function __construct(
        public string $type,
        public string $url,
        public string $filename,
    ) {}
}
