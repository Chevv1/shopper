<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product\ProductUnit;

final readonly class ProductUnit
{
    public function __construct(
        private ProductUnitId        $id,
        private ProductUnitContent   $content,
        private ProductUnitAssetIds  $assetIds,
        private ProductUnitStatus    $status,
        private ProductUnitCreatedAt $createdAt,
        private ProductUnitUpdatedAt $updatedAt,
    ) {}

    // Getters

    public function id(): ProductUnitId
    {
        return $this->id;
    }

    public function content(): ProductUnitContent
    {
        return $this->content;
    }

    public function assetIds(): ProductUnitAssetIds
    {
        return $this->assetIds;
    }

    public function status(): ProductUnitStatus
    {
        return $this->status;
    }

    public function createdAt(): ProductUnitCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ProductUnitUpdatedAt
    {
        return $this->updatedAt;
    }
}
