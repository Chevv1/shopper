<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\GetChats;

use App\Shared\Application\Query\QueryInterface;

final readonly class GetChatsQuery implements QueryInterface
{
    public function __construct(
        public string $userId,
    ) {}
}
