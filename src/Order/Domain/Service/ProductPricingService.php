<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

use App\Order\Domain\ValueObject\Order\OrderItemPrice;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;

final readonly class ProductPricingService
{
    public function getCurrentProductPrice(OrderItemProductId $productId): OrderItemPrice
    {
    }
}
