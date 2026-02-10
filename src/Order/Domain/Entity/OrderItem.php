<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Shared\Domain\ValueObject\ValueObject;

final readonly class OrderItem extends ValueObject
{
    public function __construct(
        private OrderItemId        $id,
        private OrderItemProductId $productId,
        private OrderItemQuantity  $quantity,
        private OrderItemPrice     $price,
    ) {}

    public function id(): OrderItemId
    {
        return $this->id;
    }

    public function productId(): OrderItemProductId
    {
        return $this->productId;
    }

    public function quantity(): OrderItemQuantity
    {
        return $this->quantity;
    }

    public function price(): OrderItemPrice
    {
        return $this->price;
    }

    public function subtotal(): OrderItemPrice
    {
        return $this->price->multiply($this->quantity->value());
    }
}
