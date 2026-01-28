<?php

declare(strict_types=1);

namespace App\Identity\Application\Command\CreateProfile;

use App\Shared\Application\Command\CommandInterface;

final readonly class CreateProfileCommand implements CommandInterface
{
    public function __construct(
        public string $userId,
        public string $name,
        public ?string $avatarId,
    ) {}
}
