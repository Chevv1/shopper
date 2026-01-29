<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Write;

use App\Order\Domain\Entity\Checkout;
use App\Order\Domain\Repository\CheckoutRepositoryInterface;
use App\Order\Domain\ValueObject\Checkout\CheckoutId;
use App\Order\Domain\ValueObject\Checkout\CheckoutOrderIds;
use App\Order\Domain\ValueObject\Order\OrderId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class DoctrineCheckoutRepository implements CheckoutRepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private DoctrineCheckoutOrderRepository $checkoutOrderRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function save(Checkout $checkout): void
    {
        $chatData = [
            'total_amount' => $checkout->totalAmount()->value(),
            'status' => $checkout->status()->value(),
            'paid_at' => $checkout->paidAt()->toDateTimeString(),
        ];

        $this->connection->beginTransaction();

        try {
            $updated = $this->connection->update(
                table: 'checkouts',
                data: $chatData,
                criteria: [
                    'id' => $checkout->id()->value(),
                ],
            );

            if ($updated === 0) {
                $this->connection->insert(
                    table: 'checkouts',
                    data: [
                        'id' => $checkout->id()->value(),
                        ...$chatData,
                        'created_at' => $checkout->createdAt()->toDateTimeString(),
                    ],
                );
            }

            $this->syncOrderIds(checkoutId: $checkout->id(), orderIds: $checkout->orderIds());

            $this->connection->commit();
        } catch (Exception) {
            $this->connection->rollBack();
        }
    }

    /**
     * @throws Exception
     */
    private function syncOrderIds(CheckoutId $checkoutId, CheckoutOrderIds $orderIds): void
    {
        if ($orderIds->isEmpty() === true) {
            $this->checkoutOrderRepository->deleteAllOrders(checkoutId: $checkoutId);
        } else {
            $existsOrderIds = $this->checkoutOrderRepository->getAllOrderIds(checkoutId: $checkoutId);

            $newOrderIds = new CheckoutOrderIds();

            /** @var OrderId $orderId */
            foreach ($orderIds as $orderId) {
                if ($existsOrderIds->has($orderId) === true) {
                    $existsOrderIds = $existsOrderIds->remove($orderId);
                } else {
                    $newOrderIds = $newOrderIds->add($orderId);
                }
            }

            $this->checkoutOrderRepository->deleteOrders(checkoutId: $checkoutId, orderIds: $existsOrderIds);
            $this->checkoutOrderRepository->bulkInsertOrders(checkoutId: $checkoutId, orderIds: $newOrderIds);
        }
    }
}
