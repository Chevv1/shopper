<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\UpdateCartItem;

use App\Cart\Domain\Entity\CartItemProductId;
use App\Cart\Domain\Entity\CartItemQuantity;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class UpdateCartItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(UpdateCartItemCommand $command): CommandResult
    {
        $cart = $this->cartRepository->findByOwnerId(new CartOwnerId($command->ownerId));

        $cart->updateItemQuantity(
            productId: new CartItemProductId($command->productId),
            quantity: new CartItemQuantity($command->quantity),
        );

        $this->cartRepository->save($cart);

        return CommandResult::success(entityId: $cart->id());
    }
}
