<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\RemoveItemFromCart;

use App\Shared\Application\Command\CommandInterface;

final readonly class RemoveItemFromCartCommand implements CommandInterface
{
    public function __construct(
        public string $ownerId,
        public string $productId,
    ) {}
}
