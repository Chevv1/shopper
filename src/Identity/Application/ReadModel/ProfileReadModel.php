<?php

declare(strict_types=1);

namespace App\Identity\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProfileReadModel implements ReadModelInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $avatarUrl,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar_url' => $this->avatarUrl,
        ];
    }
}
