<?php

declare(strict_types=1);

namespace App\Chat\Application\Service;

interface OrderServiceInterface
{
    public function getById(string $id): ?OrderSnapshot;
}
