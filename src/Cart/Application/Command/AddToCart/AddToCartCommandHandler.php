<?php

declare(strict_types=1);

namespace App\Cart\Application\Command\AddToCart;

use App\Cart\Application\Port\CatalogServiceInterface;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItemProductId;
use App\Cart\Domain\Entity\CartItemQuantity;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Factory\CartFactory;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;
use App\Cart\Domain\Exception\CannotAddToCartException;
use App\Cart\Domain\Exception\CartNotFoundException;

final readonly class AddToCartCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private CatalogServiceInterface $catalogService,
    ) {}

    public function __invoke(AddToCartCommand $command): CommandResult
    {
        $productId = new CartItemProductId($command->productId);

        $product = $this->catalogService->getProduct(
            productId: $productId,
        );

        if ($product === null) {
            throw CannotAddToCartException::productNotFound($productId);
        }

        if ($product->isAvailable === false) {
            throw CannotAddToCartException::productIsNotAvailable($productId);
        }

        $ownerId = new CartOwnerId($command->ownerId);

        try {
            $cart = $this->cartRepository->findByOwnerId(ownerId: $ownerId);
        } catch (CartNotFoundException) {
            $cart = CartFactory::create(
                ownerId: $ownerId,
            );
        }

        $cart->addItem(
            productId: $productId,
            price: $product->price,
            quantity: new CartItemQuantity($command->quantity),
        );

        $this->cartRepository->save($cart);

        return CommandResult::success(entityId: $cart->id());
    }
}
