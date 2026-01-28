<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\ClearCart;

use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class ClearCartCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(ClearCartCommand $command): CommandResult
    {
        $cart = $this->cartRepository->findByOwnerId(new CartOwnerId($command->ownerId));

        $cart->clear();

        $this->cartRepository->save($cart);

        return CommandResult::success(entityId: $cart->id());
    }
}
