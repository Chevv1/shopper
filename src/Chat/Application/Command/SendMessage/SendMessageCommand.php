<?php

declare(strict_types=1);

namespace App\Chat\Application\Command\SendMessage;

use App\Shared\Application\Command\CommandInterface;

final readonly class SendMessageCommand implements CommandInterface
{
    public function __construct(
        public string $chatId,
        public string $senderId,
        public string $message,
    ) {}
}
