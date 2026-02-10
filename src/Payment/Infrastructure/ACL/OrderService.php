<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\ACL;

use App\Payment\Application\Service\Order\OrderServiceInterface;
use App\Payment\Application\Service\Order\OrderSnapshot;
use App\Payment\Domain\Entity\PaymentOrderId;

final readonly class OrderService implements OrderServiceInterface
{
    public function getById(PaymentOrderId $orderId): ?OrderSnapshot
    {
        return new OrderSnapshot();
    }
}
