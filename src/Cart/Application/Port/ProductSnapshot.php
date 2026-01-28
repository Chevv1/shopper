<?php

declare(strict_types=1);

namespace App\Cart\Application\Port;

use App\Cart\Domain\Entity\CartItemProductId;
use App\Shared\Domain\ValueObject\Money;

final readonly class ProductSnapshot
{
    public function __construct(
        public CartItemProductId $id,
        public bool              $isAvailable,
        public Money             $price,
    ) {}
}
