<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\Exception\QuantityMustBePositiveException;
use App\Shared\Domain\ValueObject\Money;

final readonly class CartItem
{
    public function __construct(
        private CartItemProductId $productId,
        private CartItemQuantity  $quantity,
        private Money             $price,
    ) {}

    // Commands

    /** @throws QuantityMustBePositiveException */
    public function setQuantity(CartItemQuantity $quantity): self
    {
        return new self(
            productId: $this->productId,
            quantity: $quantity,
            price: $this->price
        );
    }

    /** @throws QuantityMustBePositiveException */
    public function increaseQuantity(int $amount): self
    {
        return new self(
            productId: $this->productId,
            quantity: $this->quantity->add($amount),
            price: $this->price
        );
    }

    // Getters

    public function productId(): CartItemProductId
    {
        return $this->productId;
    }

    public function quantity(): CartItemQuantity
    {
        return $this->quantity;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function totalPrice(): Money
    {
        return $this->price->multiply($this->quantity->value());
    }
}
