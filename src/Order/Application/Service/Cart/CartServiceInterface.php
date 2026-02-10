<?php

declare(strict_types=1);

namespace App\Order\Application\Service\Cart;

use App\Order\Domain\Entity\OrderCustomerId;

interface CartServiceInterface
{
    public function getCustomerCart(OrderCustomerId $customer): ?CartSnapshot;
}
