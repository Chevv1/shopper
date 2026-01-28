<?php

declare(strict_types=1);

namespace App\Chat\Presentation\Controller;

use App\Chat\Application\Command\SendMessage\SendMessageCommand;
use App\Chat\Application\Query\GetChats\GetChatsQuery;
use App\Chat\Presentation\Request\SendMessageRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class ChatController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function getChats(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $result = $this->queryBus->ask(
            query: new GetChatsQuery(
                userId: $user->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: $result->toArray(),
        );
    }

    public function sendMessage(
        string $chatId,
        #[MapRequestPayload] SendMessageRequest $payload
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $command = new SendMessageCommand(
            chatId: $chatId,
            senderId: $user->getUserIdentifier(),
            message: $payload->message,
        );

        $this->commandBus->dispatch($command);

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
