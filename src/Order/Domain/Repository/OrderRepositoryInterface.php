<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\OrderNotFoundException;
use App\Order\Domain\ValueObject\Order\OrderId;

interface OrderRepositoryInterface
{
    /** @throws OrderNotFoundException */
    public function findById(OrderId $id): Order;
    public function save(Order $order): void;
}
