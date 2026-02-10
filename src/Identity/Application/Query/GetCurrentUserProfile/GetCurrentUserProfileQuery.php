<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetCurrentUserProfile;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetCurrentUserProfileQuery implements QueryInterface
{
    public function __construct(
        public string $userId,
    ) {}
}
