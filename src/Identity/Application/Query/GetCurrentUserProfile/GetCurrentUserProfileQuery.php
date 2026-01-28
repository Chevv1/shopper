<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetCurrentUserProfile;

final readonly class GetCurrentUserProfileQuery
{
    public function __construct(
        public string $userId,
    )
    {
    }
}
