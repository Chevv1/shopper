<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Exception\CartNotFoundException;

interface CartRepositoryInterface
{
    /** @throws CartNotFoundException */
    public function findByOwnerId(CartOwnerId $ownerId): Cart;

    public function save(Cart $cart): void;
}
