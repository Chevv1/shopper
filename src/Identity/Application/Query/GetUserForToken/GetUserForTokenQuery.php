<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetUserForToken;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetUserForTokenQuery implements QueryInterface
{
    public function __construct(
        public string $email,
    ) {}
}
