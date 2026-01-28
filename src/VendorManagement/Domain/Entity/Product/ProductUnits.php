<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product;

use App\Shared\Domain\ValueObject\CollectionValue;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitId;
use App\VendorManagement\Domain\Exception\ProductUnitNotFoundException;

final readonly class ProductUnits extends CollectionValue
{
    protected static function itemType(): string
    {
        return ProductUnit::class;
    }

    public static function empty(): self
    {
        return new self();
    }

    public function remove(ProductUnitId $unitId): self
    {
        return $this->filter(static fn(ProductUnit $item): bool => $item->id()->equals($unitId) === false);
    }
}
