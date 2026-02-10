<?php

declare(strict_types=1);

namespace App\Payment\Application\Command\HandleWebhook;

use App\Payment\Domain\Entity\PaymentId;
use App\Payment\Domain\Exception\PaymentAlreadyProcessedException;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;
use App\Shared\Application\LoggerInterface;
use Throwable;

final readonly class HandleWebhookCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository,
        private EventBusInterface          $eventBus,
        private LoggerInterface            $logger,
    ) {}

    public function __invoke(HandleWebhookCommand $command): CommandResult
    {
        try {
            $payment = $this->paymentRepository->findById(id: new PaymentId($command->paymentId));

            try {
                if ($command->status === 'paid') {
                    $payment->markAsPaid();
                } else {
                    $payment->markAsFailed();
                }

                $this->paymentRepository->save($payment);

                $this->eventBus->publish(...$payment->releaseEvents());
            } catch (PaymentAlreadyProcessedException) {}
        } catch (Throwable $e) {
            dd($e);
            $this->logger->error(message: 'unable to handle webhook', context: ['error' => $e->getMessage()]);

            return CommandResult::failure(
                errors: [$e->getMessage()],
            );
        }

        return CommandResult::success(entityId: $payment->id());
    }
}
