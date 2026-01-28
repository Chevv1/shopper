<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Controller;

use App\Identity\Application\Command\RegisterUserIfNotExists\RegisterUserIfNotExistsCommand;
use App\Identity\Application\Query\GetUserForToken\GetUserForTokenQuery;
use App\Identity\Application\ReadModel\UserReadModel;
use App\Identity\Domain\Entity\User\RefreshToken;
use App\Identity\Domain\Entity\User\Roles;
use App\Identity\Domain\Entity\User\UserId;
use App\Identity\Domain\Service\TokenGeneratorInterface;
use App\Identity\Presentation\Request\LoginRequest;
use App\Identity\Presentation\Request\RefreshTokenRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final class AuthController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    public function login(
        #[MapRequestPayload] LoginRequest $requestDTO,
    ): JsonResponse {
        $this->commandBus->dispatch(new RegisterUserIfNotExistsCommand(
            email: $requestDTO->email,
            password: $requestDTO->password,
        ));

        /** @var UserReadModel $user */
        $user = $this->queryBus->ask(new GetUserForTokenQuery(
            email: $requestDTO->email,
        ));

        $tokens = $this->tokenGenerator->generate(
            userId: new UserId($user->id),
            roles: new Roles($user->roles),
        );

        return $this->json(
            data: $tokens->toArray(),
        );
    }

    public function refreshToken(
        #[MapRequestPayload] RefreshTokenRequest $requestDTO,
    ): JsonResponse {
        $tokens = $this->tokenGenerator->refresh(
            refreshToken: new RefreshToken($requestDTO->refreshToken),
        );

        return $this->json(
            data: $tokens->toArray(),
        );
    }
}
