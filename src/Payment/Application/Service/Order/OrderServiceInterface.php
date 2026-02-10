<?php

declare(strict_types=1);

namespace App\Payment\Application\Service\Order;

use App\Payment\Domain\Entity\PaymentOrderId;

interface OrderServiceInterface
{
    public function getById(PaymentOrderId $orderId): ?OrderSnapshot;
}
