<?php

declare(strict_types=1);

namespace App\Order\Application\Port\Catalog;

use App\Order\Domain\ValueObject\Order\OrderItemPrice;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderSellerId;

final readonly class ProductSnapshot
{
    public function __construct(
        public OrderItemProductId $id,
        public bool               $isAvailable,
        public OrderItemPrice     $price,
        public OrderSellerId      $sellerId,
    ) {}
}
