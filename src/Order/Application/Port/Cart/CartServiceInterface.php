<?php

declare(strict_types=1);

namespace App\Order\Application\Port\Cart;

use App\Order\Domain\ValueObject\Order\OrderCustomerId;

interface CartServiceInterface
{
    public function getCustomerCart(OrderCustomerId $customer): ?CartSnapshot;
}
