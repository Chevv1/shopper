<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\ClearCart;

use App\Shared\Application\Command\CommandInterface;

final readonly class ClearCartCommand implements CommandInterface
{
    public function __construct(
        public string $ownerId,
    ) {}
}
