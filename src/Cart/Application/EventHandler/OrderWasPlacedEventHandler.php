<?php

declare(strict_types=1);

namespace App\Cart\Application\EventHandler;

use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Integration\Event\OrderWasPlacedEvent;

final readonly class OrderWasPlacedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(OrderWasPlacedEvent $event): void
    {
        $cart = $this->cartRepository->findByOwnerId(new CartOwnerId($event->customerId));

        $cart->clear();

        $this->cartRepository->save($cart);
    }
}
