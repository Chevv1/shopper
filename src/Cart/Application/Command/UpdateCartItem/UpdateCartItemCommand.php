<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\UpdateCartItem;

use App\Shared\Application\Command\CommandInterface;

final readonly class UpdateCartItemCommand implements CommandInterface
{
    public function __construct(
        public string $ownerId,
        public string $productId,
        public int $quantity,
    ) {}
}
