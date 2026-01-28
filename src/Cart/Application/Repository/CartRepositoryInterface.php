<?php

declare(strict_types=1);

namespace App\Cart\Application\Repository;

use App\Cart\Application\ReadModel\CartReadModel;

interface CartRepositoryInterface
{
    public function getByOwnerId(string $ownerId): CartReadModel;
}
