<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Factory;

use App\VendorManagement\Domain\Entity\Product\Product;
use App\VendorManagement\Domain\Entity\Product\ProductCategoryId;
use App\VendorManagement\Domain\Entity\Product\ProductCreatedAt;
use App\VendorManagement\Domain\Entity\Product\ProductDescription;
use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Entity\Product\ProductImageIds;
use App\VendorManagement\Domain\Entity\Product\ProductPrice;
use App\VendorManagement\Domain\Entity\Product\ProductStatus;
use App\VendorManagement\Domain\Entity\Product\ProductTitle;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUpdatedAt;
use App\VendorManagement\Domain\Entity\Product\ProductUnits;
use App\VendorManagement\Domain\Entity\Seller\SellerId;

final readonly class ProductFactory
{
    public static function create(
        SellerId           $sellerId,
        ProductTitle       $title,
        ProductDescription $description,
        ProductCategoryId  $categoryId,
        ProductPrice       $price,
        ProductImageIds    $imageIds,
    ): Product {
        return new Product(
            id: ProductId::generate(),
            sellerId: $sellerId,
            title: $title,
            description: $description,
            categoryId: $categoryId,
            price: $price,
            imageIds: $imageIds,
            status: ProductStatus::draft(),
            isAvailable: true,
            units: ProductUnits::empty(),
            createdAt: ProductCreatedAt::now(),
            updatedAt: ProductUpdatedAt::now(),
        );
    }
}
