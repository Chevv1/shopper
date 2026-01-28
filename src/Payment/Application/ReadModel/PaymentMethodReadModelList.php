<?php

declare(strict_types=1);

namespace App\Payment\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class PaymentMethodReadModelList implements ReadModelInterface
{
    /**
     * @param PaymentMethodReadModel[] $items
     */
    public function __construct(
        public array $items,
    ) {}

    public function toArray(): array
    {
        return array_map(
            callback: static fn(PaymentMethodReadModel $item): array => $item->toArray(),
            array: $this->items,
        );
    }
}
