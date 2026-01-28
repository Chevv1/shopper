<?php

declare(strict_types=1);

namespace App\Identity\Application\Query\GetUserForToken;

use App\Identity\Application\ReadModel\UserReadModel;
use App\Identity\Application\Repository\UserRepositoryInterface;
use App\Identity\Domain\Entity\User\UserEmail;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetUserForTokenQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(GetUserForTokenQuery $query): UserReadModel
    {
        return $this->userRepository->findByEmail(new UserEmail($query->email));
    }
}
