<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class JwtUser implements UserInterface
{
    public function __construct(
        private string $userId,
        private array $roles
    ) {}

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // Ничего не делаем
    }

    public function getUserIdentifier(): string
    {
        return $this->userId;
    }
}
