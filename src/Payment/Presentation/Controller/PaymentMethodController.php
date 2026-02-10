<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Controller;

use App\Payment\Application\Command\HandleWebhook\HandleWebhookCommand;
use App\Payment\Application\Query\GetActivePaymentMethods\GetActivePaymentMethodsQuery;
use App\Payment\Application\Command\CreatePayment\CreatePaymentCommand;
use App\Payment\Application\ReadModel\PaymentMethodReadModelList;
use App\Payment\Infrastructure\Security\IsSignedWebhook;
use App\Payment\Presentation\Request\CreatePaymentRequest;
use App\Payment\Presentation\Request\HandleWebhookRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class PaymentMethodController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function listPaymentMethods(): JsonResponse
    {
        $query = new GetActivePaymentMethodsQuery();

        /** @var PaymentMethodReadModelList $paymentMethods */
        $paymentMethods = $this->queryBus->ask($query);

        return $this->json(
            data: $paymentMethods->toArray(),
        );
    }

    public function createPaymentUrl(
        #[MapRequestPayload] CreatePaymentRequest $payload,
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $result = $this->commandBus->dispatch(
            command: new CreatePaymentCommand(
                ownerId: $user->getUserIdentifier(),
                orderId: $payload->orderId,
                paymentMethodId: $payload->paymentMethodId,
                successUrl: $payload->successUrl,
            ),
        );

        return $this->json(
            data: [
                'success' => true,
                'data' => [
                    'payment' => [
                        'url' => $result->message,
                    ],
                ],
            ],
        );
    }

    #[IsSignedWebhook]
    public function handleWebhook(
        #[MapRequestPayload] HandleWebhookRequest $payload,
    ): JsonResponse {
        $result = $this->commandBus->dispatch(
            command: new HandleWebhookCommand(
                paymentId: $payload->paymentId,
                status: $payload->status,
            ),
        );

        if ($result->success === false) {
            return $this->json(
                data: [
                    'success' => false,
                ],
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        return $this->json([
            'success' => true,
        ]);
    }
}
