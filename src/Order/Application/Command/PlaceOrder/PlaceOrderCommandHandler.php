<?php

declare(strict_types=1);

namespace App\Order\Application\Command\PlaceOrder;

use App\Order\Application\Port\Cart\CartItemSnapshot;
use App\Order\Application\Port\Cart\CartServiceInterface;
use App\Order\Application\Port\Catalog\CatalogServiceInterface;
use App\Order\Application\Port\Catalog\ProductSnapshot;
use App\Order\Domain\Exception\CannotPlaceOrderException;
use App\Order\Domain\Factory\CheckoutFactory;
use App\Order\Domain\Factory\OrderFactory;
use App\Order\Domain\Factory\OrderItemFactory;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\Repository\CheckoutRepositoryInterface;
use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderItem;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderItems;
use App\Order\Domain\ValueObject\Order\OrderTotalPrice;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class PlaceOrderCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface    $orderRepository,
        private CheckoutRepositoryInterface $checkoutRepository,
        private CartServiceInterface        $cartService,
        private CatalogServiceInterface     $catalogService,
    ) {}

    public function __invoke(PlaceOrderCommand $command): CommandResult
    {
        $customerId = new OrderCustomerId($command->customerId);

        $cart = $this->cartService->getCustomerCart(customer: $customerId);
        if ($cart === null) {
            throw CannotPlaceOrderException::customerDoesntHaveCart($customerId);
        }

        $products = $this->catalogService->getProductsByIds(
            productIds: array_map(
                callback: static fn(CartItemSnapshot $item): OrderItemProductId => $item->productId,
                array: $cart->items,
            ),
        );

        $ordersData = $this->groupItemsBySeller(
            cartItems: $cart->items,
            products: $products,
            customerId: $customerId,
        );

        $orders = [];

        /** @var OrderItem[] $orderItems */
        foreach ($ordersData as $orderData) {
            $order = OrderFactory::create(
                customer: $orderData['customer'],
                items: $orderData['items'],
                totalPrice: $orderData['totalPrice'],
            );

            $this->orderRepository->save($order);

            $orders[] = $order;
        }

        $checkout = CheckoutFactory::createFromOrders($orders);

        $this->checkoutRepository->save($checkout);

        return CommandResult::success(entityId: $checkout->id());
    }

    /**
     * @param CartItemSnapshot[] $cartItems
     * @param ProductSnapshot[] $products
     * @param OrderCustomerId $customerId
     *
     * @return array
     */
    private function groupItemsBySeller(
        array $cartItems,
        array $products,
        OrderCustomerId $customerId,
    ): array {
        $grouped = [];

        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->id->value()] = $product;
        }

        foreach ($cartItems as $cartItem) {
            $productId = $cartItem->productId->value();

            if (!isset($productsById[$productId])) {
                throw CannotPlaceOrderException::productNotFound(productId: $cartItem->productId);
            }

            $product = $productsById[$productId];

            if ($product->isAvailable === false) {
                throw CannotPlaceOrderException::productUnavailable(productId: $cartItem->productId);
            }

            $sellerId = $product->sellerId->value();

            $orderItem = OrderItemFactory::create(
                productId: $product->id,
                quantity: $cartItem->quantity,
                price: $product->price,
            );

            if (array_key_exists(key: $sellerId, array: $grouped) === false) {
                $grouped[$sellerId] = [
                    'customer' => $customerId,
                    'items' => [],
                    'totalPrice' => 0,
                ];
            }

            $grouped[$sellerId]['items'][] = $orderItem;
            $grouped[$sellerId]['totalPrice'] += $orderItem->subtotal()->value();
        }

        return array_map(
            callback: static fn (array $data) => [
                'customer' => $data['customer'],
                'items' => new OrderItems($data['items']),
                'totalPrice' => new OrderTotalPrice($data['totalPriceValue'])
            ],
            array: $grouped,
        );
    }
}
