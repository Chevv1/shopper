<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class CategoryReadModel implements ReadModelInterface
{
    /**
     * @param CategoryReadModel[] $children
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $children,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'children' => array_map(
                callback: static fn(CategoryReadModel $item): array => $item->toArray(),
                array: $this->children,
            ),
        ];
    }
}
