<?php

declare(strict_types=1);

namespace App\Cart\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class CartItemReadModel implements ReadModelInterface
{
    public function __construct(
        public string $productId,
        public string $productTitle,
        public int $quantity,
        public int $unitPrice,
        public int $totalPrice,
        public bool $isAvailable,
    ) {}

    public function toArray(): array
    {
        return [
            'product' => [
                'id' => $this->productId,
                'title' => $this->productTitle,
            ],
            'quantity' => $this->quantity,
            'price' => [
                'unit' => $this->unitPrice,
                'total' => $this->totalPrice,
            ],
            'isAvailable' => $this->isAvailable,
        ];
    }
}
