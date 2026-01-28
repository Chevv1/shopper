<?php

declare(strict_types=1);

namespace App\Order\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class OrderReadModel implements ReadModelInterface
{
    public function __construct(
        public string $id,
        public string $status,
        public int $total,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
