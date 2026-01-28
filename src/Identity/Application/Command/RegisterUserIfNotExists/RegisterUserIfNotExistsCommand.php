<?php

declare(strict_types=1);

namespace App\Identity\Application\Command\RegisterUserIfNotExists;

use App\Shared\Application\Command\CommandInterface;

final readonly class RegisterUserIfNotExistsCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
