<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product;

use App\Shared\Domain\Entity\AggregateRoot;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitId;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUpdatedAt;
use App\VendorManagement\Domain\Entity\Seller\SellerId;
use App\VendorManagement\Domain\Event\ProductCreated;
use App\VendorManagement\Domain\Event\ProductPublished;
use App\VendorManagement\Domain\Event\ProductUnitAdded;
use App\VendorManagement\Domain\Event\ProductUpdated;
use App\VendorManagement\Domain\Exception\CannotPublishWithoutUnitsException;

final class Product extends AggregateRoot
{
    public function __construct(
        private readonly ProductId        $id,
        private readonly SellerId         $sellerId,
        private ProductTitle              $title,
        private ProductDescription        $description,
        private ProductCategoryId         $categoryId,
        private ProductPrice              $price,
        private ProductImageIds           $imageIds,
        private ProductStatus             $status,
        private bool                      $isAvailable,
        private readonly ProductUnits     $units,
        private readonly ProductCreatedAt $createdAt,
        private ProductUpdatedAt          $updatedAt,
    ) {
        $this->recordEvent(new ProductCreated());
    }

    // Commands

    public function updateDetails(
        ProductTitle       $title,
        ProductDescription $description,
        ProductCategoryId  $categoryId,
        ProductPrice       $price,
        ProductImageIds    $images,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->imageIds = $images;
        $this->updatedAt = ProductUpdatedAt::now();

        $this->recordEvent(new ProductUpdated());
    }

    public function addUnit(ProductUnit $unit): void
    {
        if ($this->units->count() >= 10) {
            throw new \DomainException('Maximum units limit reached');
        }

        $this->units->add($unit);

        $this->recordEvent(new ProductUnitAdded());
    }

    public function removeUnit(ProductUnitId $unitId): void
    {
        $this->units->remove($unitId);
    }

    public function publish(): void
    {
        if ($this->units->isEmpty()) {
            throw new CannotPublishWithoutUnitsException();
        }

        $this->status = ProductStatus::published();
        $this->updatedAt = ProductUpdatedAt::now();

        $this->recordEvent(new ProductPublished());
    }

    public function archive(): void
    {
        $this->isAvailable = false;
        $this->status = ProductStatus::archived();
        $this->updatedAt = ProductUpdatedAt::now();
    }

    // Getters

    public function id(): ProductId
    {
        return $this->id;
    }

    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }

    public function title(): ProductTitle
    {
        return $this->title;
    }

    public function description(): ProductDescription
    {
        return $this->description;
    }

    public function categoryId(): ProductCategoryId
    {
        return $this->categoryId;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function imageIds(): ProductImageIds
    {
        return $this->imageIds;
    }

    public function status(): ProductStatus
    {
        return $this->status;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function units(): ProductUnits
    {
        return $this->units;
    }

    public function createdAt(): ProductCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ProductUpdatedAt
    {
        return $this->updatedAt;
    }
}
