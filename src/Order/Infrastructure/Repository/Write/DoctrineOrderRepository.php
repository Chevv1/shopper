<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Write;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderCreatedAt;
use App\Order\Domain\Entity\OrderCustomerId;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\Entity\OrderItemId;
use App\Order\Domain\Entity\OrderItemPrice;
use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderItemQuantity;
use App\Order\Domain\Entity\OrderItems;
use App\Order\Domain\Entity\OrderStatus;
use App\Order\Domain\Entity\OrderTotalPrice;
use App\Order\Domain\Entity\OrderUpdatedAt;
use App\Order\Domain\Exception\OrderNotFoundException;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;

final readonly class DoctrineOrderRepository implements OrderRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    public function findById(OrderId $id): Order
    {
        $orderData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    customer_id,
                    status,
                    total_price,
                    created_at,
                    updated_at
                FROM orders 
                WHERE id = ?
            ',
            params: [$id->value()],
        );

        if (!$orderData) {
            throw new OrderNotFoundException;
        }

        $itemsData = $this->connection->fetchAllAssociative(
            query: '
                SELECT id, product_id, quantity, price 
                FROM order_items 
                WHERE order_id = ?
            ',
            params: [$orderData['id']],
        );

        return self::hydrate(
            className: Order::class,
            data: [
                'id' => new OrderId($orderData['id']),
                'customerId' => new OrderCustomerId($orderData['customer_id']),
                'status' => new OrderStatus($orderData['status']),
                'items' => new OrderItems(
                    items: array_map(
                        callback: static fn(array $orderItem) => self::hydrate(
                            className: OrderItem::class,
                            data: [
                                'id' => new OrderItemId($orderItem['id']),
                                'productId' => new OrderItemProductId($orderItem['product_id']),
                                'quantity' => new OrderItemQuantity($orderItem['quantity']),
                                'price' => new OrderItemPrice($orderItem['price']),
                            ],
                        ),
                        array: $itemsData,
                    ),
                ),
                'totalPrice' => new OrderTotalPrice($orderData['total_price']),
                'createdAt' => OrderCreatedAt::fromString($orderData['created_at']),
                'updatedAt' => OrderUpdatedAt::fromString($orderData['updated_at']),
            ],
        );
    }

    public function save(Order $order): void
    {
        $this->connection->beginTransaction();

        try {
            $this->connection->executeStatement(
                sql: '
                    INSERT INTO orders (
                        id,
                        customer_id,
                        status,
                        total_price,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        :id,
                        :customer_id,
                        :status,
                        :total_price,
                        :created_at,
                        :updated_at
                    )
                    ON CONFLICT (id)
                    DO UPDATE SET
                       status = :status,
                       updated_at = :updated_at
                ',
                params: [
                    'id' => $order->id()->value(),
                    'customer_id' => $order->customerId()->value(),
                    'status' => $order->status()->value(),
                    'total_price' => $order->totalPrice()->value(),
                    'created_at' => $order->createdAt()->toDateTimeString(),
                    'updated_at' => $order->updatedAt()->toDateTimeString(),
                ],
            );

            /** @var OrderItem $orderItem */
            foreach ($order->items() as $orderItem) {
                $this->connection->executeStatement(
                    sql: '
                        INSERT INTO order_items (
                            id,
                            order_id,
                            product_id,
                            quantity,
                            price
                        ) VALUES (
                            :id,
                            :order_id,
                            :product_id,
                            :quantity,
                            :price
                        )
                        ON CONFLICT (id)
                        DO NOTHING
                    ',
                    params: [
                        'id' => $orderItem->id()->value(),
                        'order_id' => $order->id()->value(),
                        'product_id' => $orderItem->productId()->value(),
                        'quantity' => $orderItem->quantity()->value(),
                        'price' => $orderItem->price()->value(),
                    ],
                );
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
