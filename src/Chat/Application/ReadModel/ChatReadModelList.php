<?php

declare(strict_types=1);

namespace App\Chat\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ChatReadModelList implements ReadModelInterface
{
    /**
     * @param ChatReadModel[] $items
     */
    public function __construct(
        public array $items,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: static fn(ChatReadModel $item): array => $item->toArray(),
            array: $this->items,
        );
    }
}