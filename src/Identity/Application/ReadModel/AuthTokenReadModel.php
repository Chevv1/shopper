<?php

declare(strict_types=1);

namespace App\Identity\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class AuthTokenReadModel implements ReadModelInterface
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public int $expiresIn,
    ) {
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
        ];
    }
}
