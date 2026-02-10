<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Exception\OrderNotFoundException;

interface OrderRepositoryInterface
{
    /** @throws OrderNotFoundException */
    public function findById(OrderId $id): Order;
    public function save(Order $order): void;
}
