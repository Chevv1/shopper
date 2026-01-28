<?php

declare(strict_types=1);

namespace App\Cart\Application\Query\ShowCart;

use App\Shared\Application\Query\QueryInterface;

final readonly class ShowCartQuery implements QueryInterface
{
    public function __construct(
        public string $ownerId,
    ) {}
}
