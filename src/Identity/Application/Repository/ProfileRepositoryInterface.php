<?php

declare(strict_types=1);

namespace App\Identity\Application\Repository;

use App\Identity\Application\ReadModel\ProfileReadModel;

interface ProfileRepositoryInterface
{
    public function getProfileIdByUserId(string $userId): string;
    public function getByUserId(string $userId): ProfileReadModel;
}
