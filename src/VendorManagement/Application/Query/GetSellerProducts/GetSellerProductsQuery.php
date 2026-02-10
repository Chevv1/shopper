<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Query\GetSellerProducts;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetSellerProductsQuery implements QueryInterface
{
    public function __construct(
        public string $sellerId,
    ) {}
}
