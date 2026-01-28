<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Factory;

use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitAssetIds;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitContent;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitCreatedAt;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitId;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitStatus;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitUpdatedAt;

final readonly class ProductUnitFactory
{
    public static function create(
        ProductUnitContent  $content,
        ProductUnitAssetIds $assetIds,
    ): ProductUnit {
        return new ProductUnit(
            id: ProductUnitId::generate(),
            content: $content,
            assetIds: $assetIds,
            status: ProductUnitStatus::available(),
            createdAt: ProductUnitCreatedAt::now(),
            updatedAt: ProductUnitUpdatedAt::now(),
        );
    }
}
