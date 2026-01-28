<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\Exception\ProductNotInCartException;
use App\Cart\Domain\Factory\CartItemFactory;
use App\Shared\Domain\Entity\AggregateRoot;
use App\Shared\Domain\ValueObject\Money;

final class Cart extends AggregateRoot
{
    public function __construct(
        private readonly CartId      $id,
        private readonly CartOwnerId $ownerId,
        private CartItems            $items,
    ) {}

    // Commands

    public function addItem(
        CartItemProductId $productId,
        Money             $price,
        CartItemQuantity  $quantity,
    ): void {
        try {
            $existingItem = $this->items->findByProductId($productId);

            $updatedItem = $existingItem->increaseQuantity($quantity->value());

            $this->items = $this->items->update($updatedItem);
        } catch (ProductNotInCartException) {
            $newItem = CartItemFactory::create(
                productId: $productId,
                price: $price,
                quantity: $quantity
            );

            $this->items = $this->items->add($newItem);
        }
    }

    public function updateItemQuantity(
        CartItemProductId $productId,
        CartItemQuantity  $quantity
    ): void {
        $item = $this->items->findByProductId($productId);

        if (!$item) {
            throw new ProductNotInCartException(productId: $productId);
        }

        $updatedItem = $item->setQuantity($quantity);

        $this->items = $this->items->update($updatedItem);
    }

    /** @throws ProductNotInCartException */
    public function removeItem(CartItemProductId $productId): void
    {
        $this->items->hasProduct($productId);

        $this->items = $this->items->remove($productId);
    }

    public function clear(): void
    {
        $this->items = new CartItems();
    }

    // Getters

    public function id(): CartId
    {
        return $this->id;
    }

    public function ownerId(): CartOwnerId
    {
        return $this->ownerId;
    }

    public function items(): CartItems
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function getTotalPrice(): Money
    {
        $total = 0;

        /** @var CartItem $item */
        foreach ($this->items as $item) {
            $total += $item->totalPrice()->amount();
        }

        return Money::fromAmount($total);
    }
}
