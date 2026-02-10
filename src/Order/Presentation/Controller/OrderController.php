<?php

declare(strict_types=1);

namespace App\Order\Presentation\Controller;

use App\Order\Application\Command\PlaceOrder\PlaceOrderCommand;
use App\Order\Application\Query\GetCustomerOrders\GetCustomerOrdersQuery;
use App\Order\Application\Query\GetCustomerOrder\GetCustomerOrderQuery;
use App\Order\Application\ReadModel\OrderReadModel;
use App\Order\Application\ReadModel\OrderReadModelList;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class OrderController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function showMyOrders(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        /** @var OrderReadModelList $order */
        $orders = $this->queryBus->ask(
            query: new GetCustomerOrdersQuery(
                customerId: $user->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: $orders->toArray(),
        );
    }

    public function showOrder(string $orderId): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        /** @var OrderReadModel $order */
        $order = $this->queryBus->ask(
            query: new GetCustomerOrderQuery(
                customerId: $user->getUserIdentifier(),
                orderId: $orderId,
            ),
        );

        return $this->json(
            data: $order->toArray(),
        );
    }

    public function placeOrder(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $commandResult = $this->commandBus->dispatch(
            command: new PlaceOrderCommand(
                customerId: $user->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: [
                'success' => true,
                'data' => [
                    'order' => [
                        'id' => $commandResult->entityId,
                    ],
                ]
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
