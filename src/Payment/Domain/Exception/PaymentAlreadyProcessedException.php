<?php

declare(strict_types=1);

namespace App\Payment\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class PaymentAlreadyProcessedException extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Payment already processed');
    }
}
