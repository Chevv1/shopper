<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetCurrentUserProfile;

use App\Identity\Application\ReadModel\ProfileReadModel;
use App\Identity\Application\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetCurrentUserProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProfileRepositoryInterface $profileReadRepository,
    )
    {
    }

    public function __invoke(GetCurrentUserProfileQuery $query): ProfileReadModel
    {
        return $this->profileReadRepository->getByUserId($query->userId);
    }
}
