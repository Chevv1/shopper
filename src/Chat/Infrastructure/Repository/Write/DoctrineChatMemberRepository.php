<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Repository\Write;

use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatMember;
use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMemberJoinedAt;
use App\Chat\Domain\Entity\ChatMemberRole;
use App\Chat\Domain\Entity\ChatMembers;
use App\Chat\Domain\Entity\ChatMemberUserId;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use ReflectionException;

final readonly class DoctrineChatMemberRepository
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getByChatId(ChatId $chatId): ChatMembers
    {
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
            params: [$chatId->value()],
        );

        return new ChatMembers(
            items: array_map(
                callback: static fn(array $member) => self::hydrate(
                    className: ChatMember::class,
                    data: [
                        'id' => new ChatMemberId($member['id']),
                        'userId' => new ChatMemberUserId($member['user_id']),
                        'role' => new ChatMemberRole($member['role']),
                        'joinedAt' => ChatMemberJoinedAt::fromString($member['joined_at']),
                    ],
                ),
                array: $membersData,
            ),
        );
    }

    /**
     * @throws Exception
     */
    public function save(ChatId $chatId, ChatMembers $chatMembers): void
    {
        /** @var ChatMember $member */
        foreach ($chatMembers as $member) {
            $this->connection->executeStatement(
                sql: '
                    INSERT INTO chat_members (
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
                    ON CONFLICT (id) DO NOTHING
                ',
                params: [
                    'id' => $member->id()->value(),
                    'chat_id' => $chatId->value(),
                    'user_id' => $member->userId()->value(),
                    'role' => $member->role()->value(),
                    'joined_at' => $member->joinedAt()->toDateTimeString(),
                ],
            );
        }
    }
}
