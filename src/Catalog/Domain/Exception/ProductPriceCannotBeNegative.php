<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProductPriceCannotBeNegative extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Price cannot be negative');
    }
}
