<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductReadModelList implements ReadModelInterface
{
    /**
     * @param ProductReadModel[] $products
     */
    public function __construct(
        public array $products,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: static fn(ProductReadModel $product): array => $product->toArray(),
            array: $this->products,
        );
    }
}
