<?php

declare(strict_types=1);

namespace App\Chat\Application\Repository;

use App\Chat\Application\ReadModel\ChatReadModelList;

interface ChatRepositoryInterface
{
    public function findByMemberUserId(string $memberUserId): ChatReadModelList;
}
