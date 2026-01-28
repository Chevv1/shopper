<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Repository\Write;

use App\Chat\Domain\Entity\Chat;
use App\Chat\Domain\Entity\ChatCorrelationId;
use App\Chat\Domain\Entity\ChatCorrelationType;
use App\Chat\Domain\Entity\ChatCreatedAt;
use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatMember;
use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMemberJoinedAt;
use App\Chat\Domain\Entity\ChatMemberRole;
use App\Chat\Domain\Entity\ChatMembers;
use App\Chat\Domain\Entity\ChatMemberUserId;
use App\Chat\Domain\Entity\ChatMessage;
use App\Chat\Domain\Entity\ChatMessageContent;
use App\Chat\Domain\Entity\ChatMessageCreatedAt;
use App\Chat\Domain\Entity\ChatMessageId;
use App\Chat\Domain\Entity\ChatMessages;
use App\Chat\Domain\Entity\ChatCorrelation;
use App\Chat\Domain\Entity\ChatStatus;
use App\Chat\Domain\Entity\ChatUpdatedAt;
use App\Chat\Domain\Exception\ChatNotFoundException;
use App\Chat\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;

final readonly class DoctrineChatRepository implements ChatRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    public function findById(ChatId $chatId): Chat
    {
        $chatData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    type,
                    created_at
                FROM chats
                WHERE id = ?
            ',
            params: [$chatId->value()],
        );

        if (!$chatData) {
            throw ChatNotFoundException::byId($chatId);
        }

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
            params: [$chatData['id']],
        );

        $membersData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    id,
                    user_id,
                    role,
                    joined_at
                FROM chat_members 
                WHERE chat_id = ?
            ',
            params: [$chatData['id']],
        );

        $messages = array_map(
            callback: static fn(array $message) => self::reflectionHydrate(
                className: ChatMessage::class,
                data: [
                    'id' => new ChatMessageId($message['id']),
                    'senderId' => new ChatMemberId($message['sender_id']),
                    'content' => new ChatMessageContent($message['content']),
                    'createdAt' => ChatMessageCreatedAt::fromString($message['created_at']),
                ],
            ),
            array: $messagesData,
        );

        $members = array_map(
            callback: static fn(array $member) => self::reflectionHydrate(
                className: ChatMember::class,
                data: [
                    'id' => new ChatMemberId($member['id']),
                    'userId' => new ChatMemberUserId($member['user_id']),
                    'role' => new ChatMemberRole($member['role']),
                    'joinedAt' => ChatMemberJoinedAt::fromString($member['joined_at']),
                ],
            ),
            array: $membersData,
        );

        return self::reflectionHydrate(
            className: Chat::class,
            data: [
                'id' => new ChatId($chatData['id']),
                'correlation' => new ChatCorrelation(
                    id: new ChatCorrelationId($chatData['correlation_id']),
                    type: new ChatCorrelationType($chatData['correlation_type']),
                ),
                'status' => new ChatStatus($chatData['status']),
                'newMessages' => new ChatMessages(items: $messages),
                'members' => new ChatMembers(items: $members),
                'createdAt' => ChatCreatedAt::fromString($chatData['created_at']),
                'updatedAt' => ChatUpdatedAt::fromString($chatData['updated_at']),
            ],
        );
    }

    public function save(Chat $chat): void
    {
        $this->connection->transactional(function (Connection $conn) use ($chat) {
            $conn->executeStatement(
                sql: '
                    INSERT INTO conversations (
                       id,
                       type,
                       status,
                       created_at,
                       updated_at
                    )
                    VALUES (
                        :id,
                        :type
                        :status
                        :created_at
                        :updated_at
                    )
                    ON DUPLICATE KEY UPDATE
                        status = :status,
                        updated_at = :updated_at
                ',
                params: [
                    'id' => $chat->id()->value(),
                    'correlation_id' => $chat->correlation()->id()->value(),
                    'correlation_type' => $chat->correlation()->type()->value(),
                    'status' => $chat->status()->value(),
                    'created_at' => $chat->createdAt()->toDateTimeString(),
                    'updated_at' => $chat->updatedAt()->toDateTimeString(),
                ]);

            /** @var ChatMember $member */
            foreach ($chat->members() as $member) {
                $conn->executeStatement(
                    sql: '
                        INSERT IGNORE INTO chat_members (
                            id,
                            chat_id,
                            user_id,
                            role,
                            joined_at
                        )
                        VALUES (
                            :id,
                            :chat_id,
                            :user_id,
                            :role,
                            :joined_at
                        )
                    ',
                    params: [
                        'id' => $member->id()->value(),
                        'chat_id' => $chat->id()->value(),
                        'user_id' => $member->userId()->value(),
                        'role' => $member->role()->value(),
                        'joined_at' => $member->joinedAt()->toDateTimeString(),
                    ],
                );
            }

            /** @var ChatMessage $message */
            foreach ($chat->newMessages() as $message) {
                $conn->insert(
                    table: 'chat_messages',
                    data: [
                        'id' => $message->id()->value(),
                        'chat_id' => $chat->id()->value(),
                        'sender_id' => $message->senderId(),
                        'content' => $message->content(),
                        'created_at' => $message->createdAt()->toDateTimeString(),
                    ],
                );
            }
        });
    }
}
