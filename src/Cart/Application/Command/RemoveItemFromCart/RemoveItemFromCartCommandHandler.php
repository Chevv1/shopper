<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\RemoveItemFromCart;

use App\Cart\Domain\Entity\CartItemProductId;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class RemoveItemFromCartCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(RemoveItemFromCartCommand $command): CommandResult
    {
        $cart = $this->cartRepository->findByOwnerId(new CartOwnerId($command->ownerId));

        $cart->removeItem(productId: new CartItemProductId($command->productId));

        $this->cartRepository->save($cart);

        return CommandResult::success(entityId: $cart->id());
    }
}
