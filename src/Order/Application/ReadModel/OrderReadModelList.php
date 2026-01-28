<?php

declare(strict_types=1);

namespace App\Order\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class OrderReadModelList implements ReadModelInterface
{
    /**
     * @param OrderReadModel[] $orders
     */
    public function __construct(
        public array $orders,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: static fn (OrderReadModel $order): array => $order->toArray(),
            array: $this->orders,
        );
    }
}
