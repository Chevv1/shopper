<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Repository\Write;

use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMessage;
use App\Chat\Domain\Entity\ChatMessageContent;
use App\Chat\Domain\Entity\ChatMessageCreatedAt;
use App\Chat\Domain\Entity\ChatMessageId;
use App\Chat\Domain\Entity\ChatMessages;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use ReflectionException;

final readonly class DoctrineChatMessageRepository
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function getByChatId(ChatId $chatId): ChatMessages
    {
        $messagesData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    id,
                    sender_id,
                    content,
                    created_at
                FROM chat_messages 
                WHERE chat_id = ?
            ',
            params: [$chatId->value()],
        );

        return new ChatMessages(
            items: array_map(
                callback: static fn(array $message) => self::hydrate(
                    className: ChatMessage::class,
                    data: [
                        'id' => new ChatMessageId($message['id']),
                        'senderId' => new ChatMemberId($message['sender_id']),
                        'content' => new ChatMessageContent($message['content']),
                        'createdAt' => ChatMessageCreatedAt::fromString($message['created_at']),
                    ],
                ),
                array: $messagesData,
            ),
        );
    }

    /**
     * @throws Exception
     */
    public function save(ChatId $chatId, ChatMessages $messages): void
    {
        /** @var ChatMessage $message */
        foreach ($messages as $message) {
            $this->connection->insert(
                table: 'chat_messages',
                data: [
                    'id' => $message->id()->value(),
                    'chat_id' => $chatId->value(),
                    'sender_id' => $message->senderId(),
                    'content' => $message->content(),
                    'created_at' => $message->createdAt()->toDateTimeString(),
                ],
            );
        }
    }
}
