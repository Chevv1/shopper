<?php

declare(strict_types=1);

namespace App\Order\Application\Repository;

use App\Order\Application\ReadModel\OrderReadModelList;

interface OrderRepositoryInterface
{
    public function getByCustomerId(string $customerId): OrderReadModelList;
}
