<?php

declare(strict_types=1);

namespace App\Payment\Application\Command\CreatePayment;

use App\Payment\Application\Service\Order\OrderServiceInterface;
use App\Payment\Domain\Entity\PaymentAmount;
use App\Payment\Domain\Entity\PaymentMethodId;
use App\Payment\Domain\Entity\PaymentOrderId;
use App\Payment\Domain\Entity\PaymentOwnerId;
use App\Payment\Domain\Exception\UnableToCreatePaymentException;
use App\Payment\Domain\Factory\PaymentFactory;
use App\Payment\Domain\Repository\PaymentMethodRepositoryInterface;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class CreatePaymentCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private OrderServiceInterface            $orderService,
        private PaymentRepositoryInterface       $paymentRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private PaymentGatewayInterface          $paymentGateway,
        private EventBusInterface                $eventBus,
    ) {}

    public function __invoke(CreatePaymentCommand $command): CommandResult
    {
        $orderId = new PaymentOrderId($command->orderId);

        $order = $this->orderService->getById(orderId: $orderId);
        if ($order === null) {
            throw UnableToCreatePaymentException::orderNotFound($orderId);
        }

        $paymentMethod = $this->paymentMethodRepository->getById(id: new PaymentMethodId($command->paymentMethodId));

        $payment = PaymentFactory::create(
            orderId: $orderId,
            ownerId: new PaymentOwnerId($command->ownerId),
            methodId: $paymentMethod->id(),
            amount: new PaymentAmount(0),
        );

        $paymentUrl = $this->paymentGateway->createPaymentSession(
            payment: $payment,
            successUrl: $command->successUrl,
        );

        $payment->setPaymentUrl($paymentUrl);

        $this->paymentRepository->save($payment);

        $this->eventBus->publish(...$payment->releaseEvents());

        return CommandResult::success(
            entityId: $payment->id(),
            message: $payment->paymentUrl()->value(),
        );
    }
}
