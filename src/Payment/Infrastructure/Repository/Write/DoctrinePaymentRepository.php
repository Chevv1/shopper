<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Repository\Write;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentAmount;
use App\Payment\Domain\Entity\PaymentCreatedAt;
use App\Payment\Domain\Entity\PaymentId;
use App\Payment\Domain\Entity\PaymentMethodId;
use App\Payment\Domain\Entity\PaymentOrderId;
use App\Payment\Domain\Entity\PaymentOwnerId;
use App\Payment\Domain\Entity\PaymentStatus;
use App\Payment\Domain\Entity\PaymentUpdatedAt;
use App\Payment\Domain\Entity\PaymentUrl;
use App\Payment\Domain\Exception\PaymentNotFoundException;
use App\Payment\Domain\Repository\PaymentRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use ReflectionException;
use Throwable;

final readonly class DoctrinePaymentRepository implements PaymentRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function findById(PaymentId $id): Payment
    {
        $paymentData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    order_id,
                    owner_id,
                    method_id,
                    status,
                    amount,
                    url,
                    created_at,
                    updated_at
                FROM
                    payments
                WHERE
                    id = ?
            ',
            params: [
                $id->value(),
            ],
        );

        if (empty($paymentData) === true) {
            throw PaymentNotFoundException::byId($id);
        }

        return self::hydrate(
            className: Payment::class,
            data: [
                'id' => new PaymentId($paymentData['id']),
                'orderId' => new PaymentOrderId($paymentData['order_id']),
                'ownerId' => new PaymentOwnerId($paymentData['owner_id']),
                'methodId' => new PaymentMethodId($paymentData['method_id']),
                'status' => new PaymentStatus($paymentData['status']),
                'amount' => new PaymentAmount($paymentData['amount']),
                'paymentUrl' => new PaymentUrl($paymentData['url']),
                'createdAt' => PaymentCreatedAt::fromString($paymentData['created_at']),
                'updatedAt' => PaymentUpdatedAt::fromString($paymentData['updated_at']),
            ],
        );
    }

    /**
     * @throws Throwable
     * @throws Exception
     */
    public function save(Payment $payment): void
    {
        $this->connection->beginTransaction();

        try {
            $this->connection->executeStatement(
                sql: '
                    INSERT INTO payments (
                        id,
                        order_id,
                        owner_id,
                        method_id,
                        status,
                        amount,
                        url,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        :id,
                        :orderId,
                        :ownerId,
                        :methodId,
                        :status,
                        :amount,
                        :url,
                        :createdAt,
                        :updatedAt
                    )
                    ON CONFLICT (id)
                    DO UPDATE SET
                        status = :status,
                        url = :url,
                        updated_at = :updatedAt
                ',
                params: [
                    'id' => $payment->id()->value(),
                    'orderId' => $payment->orderId()->value(),
                    'ownerId' => $payment->ownerId()->value(),
                    'methodId' => $payment->methodId()->value(),
                    'status' => $payment->status()->value(),
                    'amount' => $payment->amount()->value(),
                    'url' => $payment->paymentUrl()->value(),
                    'createdAt' => $payment->createdAt()->toDateTimeString(),
                    'updatedAt' => $payment?->updatedAt()->toDateTimeString(),
                ],
            );
        } catch (Throwable $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->connection->commit();
    }
}
