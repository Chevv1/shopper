<?php

declare(strict_types=1);

namespace App\Order\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class OrderReadModel implements ReadModelInterface
{
    /**
     * @param string $id
     * @param string $status
     * @param int $total
     * @param OrderItemReadModel[] $items
     * @param string $createdAt
     * @param string $updatedAt
     */
    public function __construct(
        public string $id,
        public string $status,
        public int $total,
        public array $items,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'items' => array_map(
                callback: static fn(OrderItemReadModel $item): array => $item->toArray(),
                array: $this->items,
            ),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
