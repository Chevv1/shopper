<?php

declare(strict_types=1);

namespace App\Identity\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class UserReadModel implements ReadModelInterface
{
    public function __construct(
        public string $id,
        public string $email,
        public array $roles,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
        ];
    }
}
