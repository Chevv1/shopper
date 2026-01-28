<?php

declare(strict_types=1);

namespace App\Order\Application\Command\PlaceOrder;

use App\Shared\Application\Command\CommandInterface;

final readonly class PlaceOrderCommand implements CommandInterface
{
    public function __construct(
        public string $customerId,
    ) {}
}
