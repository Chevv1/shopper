<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductListReadModel implements ReadModelInterface
{
    /**
     * @param ProductReadModel[] $items
     */
    public function __construct(
        public array $items,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: static fn(ProductReadModel $item): array => $item->toArray(),
            array: $this->items,
        );
    }
}
