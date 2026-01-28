<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Order;

use App\Shared\Domain\ValueObject\ValueObject;

final readonly class OrderItem extends ValueObject
{
    public function __construct(
        private OrderItemProductId $productId,
        private OrderItemQuantity  $quantity,
        private OrderItemPrice     $price,
    ) {}

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
