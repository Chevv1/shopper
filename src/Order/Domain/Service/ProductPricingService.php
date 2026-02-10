<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

use App\Order\Domain\Entity\OrderItemPrice;
use App\Order\Domain\Entity\OrderItemProductId;

final readonly class ProductPricingService
{
    public function getCurrentProductPrice(OrderItemProductId $productId): OrderItemPrice
    {
    }
}
