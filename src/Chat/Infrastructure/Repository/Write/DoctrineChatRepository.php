<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Repository\Write;

use App\Chat\Domain\Entity\Chat;
use App\Chat\Domain\Entity\ChatCorrelationId;
use App\Chat\Domain\Entity\ChatCorrelationType;
use App\Chat\Domain\Entity\ChatCreatedAt;
use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatCorrelation;
use App\Chat\Domain\Entity\ChatStatus;
use App\Chat\Domain\Entity\ChatUpdatedAt;
use App\Chat\Domain\Exception\ChatNotFoundException;
use App\Chat\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use ReflectionException;
use Throwable;

final readonly class DoctrineChatRepository implements ChatRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection                    $connection,
        private DoctrineChatMessageRepository $messageRepository,
        private DoctrineChatMemberRepository  $memberRepository,
    ) {}

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function findById(ChatId $chatId): Chat
    {
        $chatData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    correlation_id,
                    correlation_type,
                    status,
                    created_at,
                    updated_at
                FROM chats
                WHERE id = ?
            ',
            params: [$chatId->value()],
        );

        if (!$chatData) {
            throw ChatNotFoundException::byId($chatId);
        }

        return self::hydrate(
            className: Chat::class,
            data: [
                'id' => new ChatId($chatData['id']),
                'correlation' => new ChatCorrelation(
                    id: new ChatCorrelationId($chatData['correlation_id']),
                    type: new ChatCorrelationType($chatData['correlation_type']),
                ),
                'status' => new ChatStatus($chatData['status']),
                'newMessages' => $this->messageRepository->getByChatId($chatId),
                'members' => $this->memberRepository->getByChatId($chatId),
                'createdAt' => ChatCreatedAt::fromString($chatData['created_at']),
                'updatedAt' => ChatUpdatedAt::fromString($chatData['updated_at']),
            ],
        );
    }

    /**
     * @throws Throwable
     */
    public function save(Chat $chat): void
    {
        $this->connection->beginTransaction();

        try {
            $this->connection->executeStatement(
                sql: '
                    INSERT INTO chats (
                       id,
                       correlation_id,
                       correlation_type,
                       status,
                       created_at,
                       updated_at
                    )
                    VALUES (
                        :id,
                        :correlation_id,
                        :correlation_type,
                        :status,
                        :created_at,
                        :updated_at
                    )
                    ON CONFLICT (id)
                    DO UPDATE SET
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
                ],
            );

            $this->memberRepository->save(chatId: $chat->id(), chatMembers: $chat->members());
            $this->messageRepository->save(chatId: $chat->id(), messages: $chat->newMessages());
        } catch (Throwable $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->connection->commit();
    }
}
