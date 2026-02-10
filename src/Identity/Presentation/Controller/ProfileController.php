<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Controller;

use App\Identity\Application\Command\CreateProfile\CreateProfileCommand;
use App\Identity\Application\Query\GetCurrentUserProfile\GetCurrentUserProfileQuery;
use App\Identity\Application\ReadModel\ProfileReadModel;
use App\Identity\Domain\Exception\ProfileNotFound;
use App\Identity\Presentation\Request\CreateProfileForCurrentUser;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class ProfileController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function showMyProfile(): JsonResponse
    {
        $query = new GetCurrentUserProfileQuery(
            userId: $this->getUser()->getUserIdentifier(),
        );

        try {
            /** @var ProfileReadModel $profile */
            $profile = $this->queryBus->ask(query: $query);
        } catch (HandlerFailedException $e) {
            dd($e);
            $busException = $e->getPrevious();

            if ($busException instanceof ProfileNotFound) {
                return $this->json(
                    data: [
                        'success' => false,
                        'error' => 'Profile is not exist',
                    ],
                    status: Response::HTTP_NOT_FOUND,
                );
            }

            return $this->json(
                data: [
                    'success' => false,
                    'error' => $e->getMessage(),
                ],
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return $this->json(
            data: $profile->toArray(),
        );
    }

    public function createProfileForCurrentUser(
        #[MapRequestPayload] CreateProfileForCurrentUser $requestDTO,
    ): JsonResponse
    {
        $result = $this->commandBus->dispatch(
            command: new CreateProfileCommand(
                userId: $this->getUser()->getUserIdentifier(),
                name: $requestDTO->name,
                avatarId: $requestDTO->avatarId,
            ),
        );

        if ($result->success !== true) {
            return $this->json(
                data: [
                    'success' => false,
                    'message' => $result->message,
                    'errors' => $result->errors,
                ],
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
