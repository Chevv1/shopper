<?php

declare(strict_types=1);

namespace App\Order\Application\Command\PlaceOrder;

use App\Order\Application\Service\Cart\CartItemSnapshot;
use App\Order\Application\Service\Cart\CartServiceInterface;
use App\Order\Application\Service\Catalog\CatalogServiceInterface;
use App\Order\Application\Service\Catalog\ProductSnapshot;
use App\Order\Domain\Entity\OrderCustomerId;
use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderItems;
use App\Order\Domain\Exception\CannotPlaceOrderException;
use App\Order\Domain\Factory\OrderFactory;
use App\Order\Domain\Factory\OrderItemFactory;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Shared\Application\Bus\EventBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class PlaceOrderCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private CartServiceInterface     $cartService,
        private CatalogServiceInterface  $catalogService,
        private EventBusInterface        $eventBus,
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

        /** @var array<string, ProductSnapshot> $productsById */
        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->id->value()] = $product;
        }

        $items = [];

        foreach ($cart->items as $item) {
            if (array_key_exists(key: $item->productId->value(), array: $productsById) === false) {
                throw CannotPlaceOrderException::productNotFound(productId: $item->productId);
            }

            $product = $productsById[$item->productId->value()];

            $orderItem = OrderItemFactory::create(
                productId: $product->id,
                quantity: $item->quantity,
                price: $product->price,
            );

            $items[] = $orderItem;
        }

        $order = OrderFactory::create(
            customer: $cart->customerId,
            items: new OrderItems($items),
        );

        $this->orderRepository->save($order);

        $this->eventBus->publish(...$order->releaseEvents());

        return CommandResult::success(entityId: $order->id());
    }
}
