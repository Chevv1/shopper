<?php

declare(strict_types=1);

namespace App\Cart\Application\EventHandler;

use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Infrastructure\Event\OrderPlacedIntegrationEvent;

final readonly class OrderPlacedHandler implements EventHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(OrderPlacedIntegrationEvent $event): void
    {
        $cart = $this->cartRepository->findByOwnerId(new CartOwnerId($event->customerId));

        $cart->clear();

        $this->cartRepository->save($cart);
    }
}
