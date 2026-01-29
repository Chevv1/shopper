<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Repository\Read;

use App\Chat\Application\ReadModel\ChatReadModel;
use App\Chat\Application\ReadModel\ChatReadModelList;
use App\Chat\Application\Repository\ChatRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineChatRepository implements ChatRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function findByMemberUserId(string $memberUserId): ChatReadModelList
    {
        $chatsData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    id,
                    status,
                    COUNT(m.id) as count_members
                FROM
                    chats c
                JOIN
                    chat_members m ON m.chat_id = c.id
                WHERE
                    m.user_id = :memberUserId
                GROUP BY
                    c.id
                ORDER BY
                    c.created_at DESC
            ',
            params: [
                'memberUserId' => $memberUserId,
            ],
        );

        return new ChatReadModelList(
            items: array_map(
                callback: static fn(array $chatData) => new ChatReadModel(
                    id: $chatData['id'],
                    status: $chatData['status'],
                ),
                array: $chatsData,
            ),
        );
    }
}
