<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Entity;

use App\Shared\Domain\Entity\AggregateRoot;

final class Product extends AggregateRoot
{
    public function __construct(
        private readonly ProductId          $id,
        private readonly ProductTitle       $title,
        private readonly ProductDescription $description,
        private readonly ProductPrice       $price,
        private readonly ProductImageId     $image,
        private readonly ProductSellerId    $sellerId,
        private readonly ProductCreatedAt   $createdAt,
        private readonly ProductUpdatedAt   $updatedAt,
    ) {}

    public function id(): ProductId
    {
        return $this->id;
    }

    public function title(): ProductTitle
    {
        return $this->title;
    }

    public function description(): ProductDescription
    {
        return $this->description;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function image(): ProductImageId
    {
        return $this->image;
    }

    public function sellerId(): ProductSellerId
    {
        return $this->sellerId;
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
