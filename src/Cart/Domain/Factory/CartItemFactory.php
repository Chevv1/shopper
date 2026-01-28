<?php

declare(strict_types=1);

namespace App\Cart\Domain\Factory;

use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Entity\CartItemProductId;
use App\Cart\Domain\Entity\CartItemQuantity;
use App\Cart\Domain\Exception\QuantityMustBePositiveException;
use App\Shared\Domain\ValueObject\Money;

final readonly class CartItemFactory
{
    /** @throws QuantityMustBePositiveException */
    public static function create(
        CartItemProductId $productId,
        Money             $price,
        CartItemQuantity  $quantity
    ): CartItem {
        return new CartItem(
            productId: $productId,
            quantity: $quantity,
            price: $price,
        );
    }
}
