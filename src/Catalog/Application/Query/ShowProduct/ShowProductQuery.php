<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\ShowProduct;

use App\Shared\Application\Query\QueryInterface;

final readonly class ShowProductQuery implements QueryInterface
{
    public function __construct(
        public string $productId,
    )
    {
    }
}
