<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\GetChats;

use App\Chat\Application\ReadModel\ChatReadModelList;
use App\Chat\Application\Repository\ChatRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetChatsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
    ) {}

    public function __invoke(GetChatsQuery $query): ChatReadModelList
    {
        return $this->chatRepository->findByMemberUserId(
            memberUserId: $query->userId,
        );
    }
}
