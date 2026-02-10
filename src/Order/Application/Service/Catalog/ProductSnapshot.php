<?php

declare(strict_types=1);

namespace App\Order\Application\Service\Catalog;

use App\Order\Domain\Entity\OrderItemPrice;
use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderSellerId;

final readonly class ProductSnapshot
{
    public function __construct(
        public OrderItemProductId $id,
        public bool               $isAvailable,
        public OrderItemPrice     $price,
        public OrderSellerId      $sellerId,
    ) {}
}
