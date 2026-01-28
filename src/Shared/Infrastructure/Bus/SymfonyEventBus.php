<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Domain\Event\DomainEvent;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $bus
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(DomainEvent $event): void
    {
        $this->bus->dispatch($event);
    }
}
