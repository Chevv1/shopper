<?php

declare(strict_types=1);

namespace App\Chat\Domain\Repository;

use App\Chat\Domain\Entity\Chat;
use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Exception\ChatNotFoundException;

interface ChatRepositoryInterface
{
    public function save(Chat $chat): void;

    /** @throws ChatNotFoundException */
    public function findById(ChatId $chatId): Chat;
}
