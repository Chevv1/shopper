<?php

declare(strict_types=1);

namespace App\Cart\Domain\Factory;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\Entity\CartItems;
use App\Cart\Domain\Entity\CartOwnerId;

final readonly class CartFactory
{
    public static function create(
        CartOwnerId $ownerId,
    ): Cart {
        return new Cart(
            id: CartId::generate(),
            ownerId: $ownerId,
            items: new CartItems(),
        );
    }
}
