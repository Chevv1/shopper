<?php

declare(strict_types=1);

namespace App\Chat\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ChatReadModel implements ReadModelInterface
{
    public function __construct(
        public string $id,
        public string $status,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }
}
