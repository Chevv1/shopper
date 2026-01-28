<?php

declare(strict_types=1);

namespace App\Shared\Application\Command;

use App\Shared\Domain\ValueObject\IdValue;

final readonly class CommandResult
{
    private function __construct(
        public bool     $success,
        public ?string $entityId = null,
        public array    $errors = [],
        public ?string  $message = null,
    ) {}

    public static function success(IdValue $entityId, ?string $message = null): self
    {
        return new self(true, $entityId->value(), [], $message);
    }

    public static function failure(array $errors, ?string $message = null): self
    {
        return new self(false, null, $errors, $message);
    }
}
