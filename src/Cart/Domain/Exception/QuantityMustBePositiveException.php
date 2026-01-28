<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class QuantityMustBePositiveException extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Quantity must be positive');
    }
}
