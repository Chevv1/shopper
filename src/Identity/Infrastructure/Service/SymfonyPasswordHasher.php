<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Service;

use App\Identity\Domain\Entity\User\HashedPassword;
use App\Identity\Domain\Entity\User\PlainPassword;
use App\Identity\Domain\Service\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function hash(PlainPassword $plainPassword): HashedPassword
    {
        $hashedPassword = $this->hasher->hashPassword(
            user: new class implements PasswordAuthenticatedUserInterface {
                private ?string $password = null;

                public function getPassword(): ?string
                {
                    return $this->password;
                }

                public function setPassword(string $password): void
                {
                    $this->password = $password;
                }
            },
            plainPassword: $plainPassword->value(),
        );

        return new HashedPassword($hashedPassword);
    }

    public function verify(PlainPassword $plainPassword, HashedPassword $hashedPassword): bool
    {
        return password_verify(
            password: $plainPassword->value(),
            hash: $hashedPassword->value(),
        );
    }
}
