<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Checkout;

interface CheckoutRepositoryInterface
{
    public function save(Checkout $checkout): void;
}
