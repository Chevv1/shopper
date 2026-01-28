<?php

declare(strict_types=1);

namespace App\Order\Application\Query\GetCustomerOrders;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetCustomerOrdersCommand implements QueryInterface
{
    public function __construct(
        public string $customerId,
    ) {}
}
