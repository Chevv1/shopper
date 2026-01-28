<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\Exception\ProductNotInCartException;
use App\Shared\Domain\ValueObject\CollectionValue;

final readonly class CartItems extends CollectionValue
{
    protected static function itemType(): string
    {
        return CartItem::class;
    }

    public function findByProductId(CartItemProductId $productId): ?CartItem
    {
        /** @var CartItem $item */
        return array_find(
            array: $this->items,
            callback: static fn($item): bool => $item->productId()->equals($productId)
        ) ?? throw new ProductNotInCartException(productId: $productId);
    }

    public function hasProduct(CartItemProductId $productId): void
    {
        $this->findByProductId($productId);
    }

    public function update(CartItem $updatedItem): self
    {
        $items = array_map(
            callback: static fn(CartItem $item): CartItem => $item->productId()->equals($updatedItem->productId())
                ? $updatedItem
                : $item,
            array: $this->items,
        );

        return new self($items);
    }

    public function remove(CartItemProductId $productId): self
    {
        return $this->filter(static fn(CartItem $item): bool => $item->productId()->equals($productId) === false);
    }
}
