<?php

declare(strict_types=1);

namespace App\Order\Application\Port\Cart;

use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderItemQuantity;

final readonly class CartItemSnapshot
{
    public function __construct(
        public OrderItemProductId $productId,
        public OrderItemQuantity $quantity,
    ) {}
}
