<?php

declare(strict_types=1);

namespace App\Order\Application\Service\Cart;

use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderItemQuantity;

final readonly class CartItemSnapshot
{
    public function __construct(
        public OrderItemProductId $productId,
        public OrderItemQuantity $quantity,
    ) {}
}
