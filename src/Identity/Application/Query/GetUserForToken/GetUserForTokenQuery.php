<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetUserForToken;

final readonly class GetUserForTokenQuery
{
    public function __construct(
        public string $email,
    )
    {
    }
}
