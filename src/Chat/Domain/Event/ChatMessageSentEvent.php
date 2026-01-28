<?php

declare(strict_types=1);

namespace App\Chat\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final readonly class ChatMessageSentEvent extends DomainEvent
{
}
