<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Seller;

use App\Shared\Domain\Entity\AggregateRoot;

final class Seller extends AggregateRoot
{
    public function __construct(
        private readonly SellerId $id,
    ) {}

    public function id(): SellerId
    {
        return $this->id;
    }
}
