<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\AddToCart;

use App\Shared\Application\Command\CommandInterface;

final readonly class AddToCartCommand implements CommandInterface
{
    public function __construct(
        public string $ownerId,
        public string $productId,
        public int    $quantity,
    ) {}
}
