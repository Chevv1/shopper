<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Write;

use App\Order\Domain\ValueObject\Checkout\CheckoutId;
use App\Order\Domain\ValueObject\Checkout\CheckoutOrderIds;
use App\Order\Domain\ValueObject\Order\OrderId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class DoctrineCheckoutOrderRepository
{
    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws Exception
     */
    public function getAllOrderIds(CheckoutId $checkoutId): CheckoutOrderIds
    {
        $orderIds = $this->connection->fetchFirstColumn(
            query: '
                SELECT order_id
                FROM checkout_orders
                WHERE checkout_id = ?
            ',
            params: [
                $checkoutId->value(),
            ],
        );

        return new CheckoutOrderIds($orderIds);
    }

    /**
     * @throws Exception
     */
    public function bulkInsertOrders(CheckoutId $checkoutId, CheckoutOrderIds $orderIds): void
    {
        $values = implode(
            separator: ', ',
            array: array_fill(
                start_index: 0,
                count: $orderIds->count(),
                value: '(?, ?)',
            ),
        );

        $params = [];

        /** @var OrderId $orderId */
        foreach ($orderIds as $orderId) {
            $params[] = $checkoutId->value();
            $params[] = $orderId->value();
        }

        $this->connection->executeStatement(
            sql: "
                INSERT INTO checkout_orders (checkout_id, order_id)
                VALUES $values
            ",
            params: $params,
        );
    }

    /**
     * @throws Exception
     */
    public function deleteAllOrders(CheckoutId $checkoutId): void
    {
        $this->connection->delete(
            table: 'checkout_orders',
            criteria: [
                'checkout_id' => $checkoutId->value(),
            ],
        );
    }

    /**
     * @throws Exception
     */
    public function deleteOrders(CheckoutId $checkoutId, CheckoutOrderIds $orderIds): void
    {
        $this->connection->delete(
            table: 'checkout_orders',
            criteria: [
                'checkout_id' => $checkoutId->value(),
                'order_id' => $orderIds->map(static fn(OrderId $orderId): string => $orderId->value()),
            ],
        );
    }
}
