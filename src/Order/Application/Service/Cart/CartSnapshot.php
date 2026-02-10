<?php

declare(strict_types=1);

namespace App\Order\Application\Service\Cart;

use App\Order\Domain\Entity\OrderCustomerId;
use App\Order\Domain\Entity\OrderId;

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
