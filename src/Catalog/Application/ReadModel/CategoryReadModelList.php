<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class CategoryReadModelList implements ReadModelInterface
{
    /**
     * @param CategoryReadModel[] $items
     */
    public function __construct(
        public array $items,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: fn(CategoryReadModel $item): array => $item->toArray(),
            array: $this->items,
        );
    }
}
