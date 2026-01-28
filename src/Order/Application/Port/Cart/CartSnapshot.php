<?php

declare(strict_types=1);

namespace App\Order\Application\Port\Cart;

use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderId;

final readonly class CartSnapshot
{
    /**
     * @param CartItemSnapshot[] $items
     */
    public function __construct(
        public OrderId $id,
        public OrderCustomerId $customerId,
        public array $items,
    ) {}
}
