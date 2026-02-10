<?php

declare(strict_types=1);

namespace App\Order\Application\EventHandler;

use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Integration\Event\PaymentWasPayedEvent;

final readonly class PaymentWasPayedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function __invoke(PaymentWasPayedEvent $event): void
    {
        $order = $this->orderRepository->findById(id: new OrderId($event->orderId));

        $order->markAsPaid();

        $this->orderRepository->save($order);
    }
}
