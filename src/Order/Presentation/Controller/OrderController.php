<?php

declare(strict_types=1);

namespace App\Order\Presentation\Controller;

use App\Order\Application\Command\PlaceOrder\OrderItemDTO;
use App\Order\Application\Command\PlaceOrder\PlaceOrderCommand;
use App\Order\Application\Query\GetCustomerOrders\GetCustomerOrdersCommand;
use App\Order\Presentation\Request\PlaceOrderRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

        $orders = $this->queryBus->ask(
            query: new GetCustomerOrdersCommand(
                customerId: $user->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: $orders->toArray(),
        );
    }

    public function placeOrder(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $this->commandBus->dispatch(
            command: new PlaceOrderCommand(
                customerId: $user->getUserIdentifier(),
            ),
        );

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
