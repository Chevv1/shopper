<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class OrderItemPriceIsNegativeException extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Order item price cannot be negative');
    }
}
