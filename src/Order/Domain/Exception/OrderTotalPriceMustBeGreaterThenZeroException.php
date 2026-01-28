<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class OrderTotalPriceMustBeGreaterThenZeroException extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: "Order total price must be greater than zero.");
    }
}
