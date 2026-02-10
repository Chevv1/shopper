<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class CartReadModel implements ReadModelInterface
{
    /**
     * @param CartItemReadModel[] $items
     */
    public function __construct(
        public array $items,
        public int $totalAmount,
        public int $totalItems,
    ) {}

    public function toArray(): array
    {
        return [
            'items' => array_map(
                callback: static fn(CartItemReadModel $item): array => $item->toArray(),
                array: $this->items,
            ),
            'total_amount' => $this->totalAmount,
            'total_items' => $this->totalItems,
        ];
    }
}
