<?php

declare(strict_types=1);

namespace App\Chat\Application\EventHandler;

use App\Chat\Domain\Entity\ChatCorrelation;
use App\Chat\Domain\Entity\ChatCorrelationId;
use App\Chat\Domain\Entity\ChatCorrelationType;
use App\Chat\Domain\Entity\ChatMembers;
use App\Chat\Domain\Entity\ChatMemberUserId;
use App\Chat\Domain\Factory\ChatFactory;
use App\Chat\Domain\Factory\ChatMemberFactory;
use App\Chat\Domain\Repository\ChatRepositoryInterface;
use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Infrastructure\Event\OrderPlacedIntegrationEvent;

final readonly class OrderPlacedHandler implements EventHandlerInterface
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
    ) {}

    public function __invoke(OrderPlacedIntegrationEvent $event): void
    {
        $chat = ChatFactory::create(
            correlation: new ChatCorrelation(
                id: new ChatCorrelationId($event->orderId),
                type: ChatCorrelationType::order(),
            ),
            members: new ChatMembers([
                ChatMemberFactory::createSeller(
                    userId: new ChatMemberUserId($event->sellerId),
                ),
                ChatMemberFactory::createCustomer(
                    userId: new ChatMemberUserId($event->customerId),
                ),
            ]),
        );

        $this->chatRepository->save($chat);
    }
}
