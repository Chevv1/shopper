<?php

declare(strict_types=1);

namespace App\Chat\Application\Command\SendMessage;

use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMessageContent;
use App\Chat\Domain\Factory\ChatMessageFactory;
use App\Chat\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class SendMessageCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
    ) {}

    public function __invoke(SendMessageCommand $command): CommandResult
    {
        $chat = $this->chatRepository->findById(chatId: new ChatId($command->chatId));

        $chat->sendMessage(
            message: ChatMessageFactory::create(
                senderId: new ChatMemberId($command->senderId),
                content: new ChatMessageContent($command->message),
            ),
        );

        $this->chatRepository->save($chat);

        return CommandResult::success(entityId: $chat->id());
    }
}
