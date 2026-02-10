<?php

declare(strict_types=1);

namespace App\Chat\Application\Service;

final readonly class OrderSnapshot
{
    /**
     * @param string $id
     * @param OrderItemSnapshot[] $items
     */
    public function __construct(
        public string $id,
        public array $items,
    ) {}
}
