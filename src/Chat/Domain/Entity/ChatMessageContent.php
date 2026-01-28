<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ChatMessageContent extends StringValue
{
    protected function validate(): void
    {
    }
}
