<?php

declare(strict_types=1);

namespace App\Chat\Application\Service;

final readonly class OrderItemSnapshot
{
    public function __construct(
        public string $id,
        public string $sellerId,
    ) {}
}
