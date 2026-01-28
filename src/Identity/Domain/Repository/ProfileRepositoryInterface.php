<?php

declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Identity\Domain\Entity\Profile\Profile;

interface ProfileRepositoryInterface
{
    public function save(Profile $profile): void;
}
