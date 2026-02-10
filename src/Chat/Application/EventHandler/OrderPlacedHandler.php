<?php

declare(strict_types=1);

namespace App\Chat\Application\EventHandler;

use App\Chat\Application\Service\OrderServiceInterface;
use App\Chat\Domain\Entity\ChatCorrelation;
use App\Chat\Domain\Entity\ChatCorrelationId;
use App\Chat\Domain\Entity\ChatCorrelationType;
use App\Chat\Domain\Entity\ChatMembers;
use App\Chat\Domain\Entity\ChatMemberUserId;
use App\Chat\Domain\Factory\ChatFactory;
use App\Chat\Domain\Factory\ChatMemberFactory;
use App\Chat\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Integration\Event\OrderWasPlacedEvent;

final readonly class OrderPlacedHandler implements EventHandlerInterface
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
        private OrderServiceInterface   $orderService,
    ) {}

    public function __invoke(OrderWasPlacedEvent $event): void
    {
        $order = $this->orderService->getById(id: $event->orderId);
        if ($order === null || count($order->items) === 0) {
            return;
        }

        $correlation = new ChatCorrelation(
            id: new ChatCorrelationId($order->id),
            type: ChatCorrelationType::order(),
        );

        $customerMember = ChatMemberFactory::createCustomer(
            userId: new ChatMemberUserId($event->customerId),
        );

        foreach ($order->items as $orderItem) {
            $chat = ChatFactory::create(
                correlation: $correlation,
                members: new ChatMembers([
                    ChatMemberFactory::createSeller(
                        userId: new ChatMemberUserId($orderItem->sellerId),
                    ),
                    $customerMember,
                ]),
            );

            $this->chatRepository->save($chat);
        }
    }
}
