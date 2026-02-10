<?php

declare(strict_types=1);

namespace App\Order\Application\Query\GetCustomerOrder;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetCustomerOrderQuery implements QueryInterface
{
    public function __construct(
        public string $customerId,
        public string $orderId,
    ) {}
}
