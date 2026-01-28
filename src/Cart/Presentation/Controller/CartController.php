<?php

declare(strict_types=1);

namespace App\Cart\Presentation\Controller;

use App\Cart\Application\Command\AddToCart\AddToCartCommand;
use App\Cart\Application\Command\ClearCart\ClearCartCommand;
use App\Cart\Application\Command\RemoveItemFromCart\RemoveItemFromCartCommand;
use App\Cart\Application\Command\UpdateCartItem\UpdateCartItemCommand;
use App\Cart\Application\Query\ShowCart\ShowCartQuery;
use App\Cart\Presentation\Request\AddToCartRequest;
use App\Cart\Presentation\Request\UpdateCartItemRequest;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class CartController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function showCart(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $query = new ShowCartQuery(
            ownerId: $user->getUserIdentifier(),
        );

        $cart = $this->queryBus->ask(query: $query);

        return $this->json(
            data: $cart->toArray(),
        );
    }

    public function addToCart(#[MapRequestPayload] AddToCartRequest $payload): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $command = new AddToCartCommand(
            ownerId: $user->getUserIdentifier(),
            productId: $payload->productId,
            quantity: $payload->quantity,
        );

        $this->commandBus->dispatch(command: $command);

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }

    public function updateCartItem(
        string $productId,
        #[MapRequestPayload] UpdateCartItemRequest $payload,
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $command = new UpdateCartItemCommand(
            ownerId: $user->getUserIdentifier(),
            productId: $productId,
            quantity: $payload->quantity,
        );

        $this->commandBus->dispatch(command: $command);

        return $this->json(
            data: [
                'success' => true,
            ],
        );
    }

    public function deleteCartItem(string $productId): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $command = new RemoveItemFromCartCommand(
            ownerId: $user->getUserIdentifier(),
            productId: $productId,
        );

        $this->commandBus->dispatch(command: $command);

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_OK,
        );
    }

    public function clearCart(): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $command = new ClearCartCommand(ownerId: $user->getUserIdentifier());

        $this->commandBus->dispatch(command: $command);

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_OK,
        );
    }
}
