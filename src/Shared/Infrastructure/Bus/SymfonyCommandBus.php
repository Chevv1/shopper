<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Application\Command\CommandResult;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(object $command): CommandResult
    {
        $envelope = $this->bus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }
}
