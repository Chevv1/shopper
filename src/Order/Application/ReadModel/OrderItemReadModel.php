<?php

declare(strict_types=1);

namespace App\Order\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class OrderItemReadModel implements ReadModelInterface
{
    public function __construct(
        public string $id,
        public string $productId,
        public string $productName,
        public int $quantity,
        public int $price,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->productId,
                'name' => $this->productName,
            ],
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
