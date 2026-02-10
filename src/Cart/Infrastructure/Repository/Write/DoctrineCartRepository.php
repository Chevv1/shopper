<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository\Write;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Entity\CartItemProductId;
use App\Cart\Domain\Entity\CartItemQuantity;
use App\Cart\Domain\Entity\CartItems;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\Money;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Throwable;

final readonly class DoctrineCartRepository implements CartRepositoryInterface
{
    public function __construct(
        private Connection      $connection,
        private LoggerInterface $logger,
    ) {}

    public function findByOwnerId(CartOwnerId $ownerId): Cart
    {
        $data = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    user_id
                FROM carts 
                WHERE user_id = ?
            ',
            params: [$ownerId->value()],
        );

        if (!$data) {
            throw new CartNotFoundException();
        }

        return $this->hydrate($data);
    }

    /**
     * @throws Throwable
     * @throws Exception
     */
    public function save(Cart $cart): void
    {
        $this->connection->beginTransaction();

        try {
            if ($this->isCartExists($cart)) {
                $this->clearCart($cart);
            } else {
                $this->createCart($cart);
            }

            $this->createCartItems(cart: $cart, items: $cart->items());

            $this->connection->commit();
        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    private function isCartExists(Cart $cart): bool
    {
        return $this->connection->fetchOne(
            query: 'SELECT id FROM carts WHERE id = ?',
            params: [$cart->id()->value()],
        ) !== false;
    }

    private function createCart(Cart $cart): void
    {
        $this->connection->insert(
            table: 'carts',
            data: [
                'id' => $cart->id()->value(),
                'user_id' => $cart->ownerId()->value(),
            ],
        );
    }

    private function clearCart(Cart $cart): void
    {
        $this->connection->executeStatement(
            sql: 'DELETE FROM cart_items WHERE cart_id = ?',
            params: [$cart->id()->value()],
        );
    }

    private function createCartItems(Cart $cart, CartItems $items): void
    {
        foreach ($items as $item) {
            $this->createCartItem(cart: $cart, item: $item);
        }
    }

    private function createCartItem(Cart $cart, CartItem $item): void
    {
        $this->connection->insert(
            table: 'cart_items',
            data: [
                'cart_id' => $cart->id()->value(),
                'product_id' => $item->productId()->value(),
                'quantity' => $item->quantity()->value(),
                'price' => $item->price()->amount(),
            ],
        );
    }

    private function hydrate(array $data): Cart
    {
        $items = $this->connection->fetchAllAssociative(
            query: '
                SELECT product_id, quantity, price 
                FROM cart_items 
                WHERE cart_id = ?
            ',
            params: [$data['id']],
        );

        $cartReflection = new ReflectionClass(objectOrClass: Cart::class);
        $cart = $cartReflection->newInstanceWithoutConstructor();

        $cartReflection->getProperty(name: 'id')->setValue(
            objectOrValue: $cart,
            value: new CartId($data['id']),
        );

        $cartReflection->getProperty(name: 'ownerId')->setValue(
            objectOrValue: $cart,
            value: new CartOwnerId($data['user_id']),
        );

        $itemsReflection = new ReflectionClass(objectOrClass: CartItems::class);
        $cartItems = $itemsReflection->newInstanceWithoutConstructor();

        $itemsReflection->getProperty('items')->setValue(
            objectOrValue: $cartItems,
            value: array_map(
                callback: static function (array $cartItem) {
                    $itemReflection = new ReflectionClass(objectOrClass: CartItem::class);
                    $item = $itemReflection->newInstanceWithoutConstructor();

                    $itemReflection->getProperty(name: 'productId')->setValue(
                        objectOrValue: $item,
                        value: new CartItemProductId($cartItem['product_id']),
                    );

                    $itemReflection->getProperty(name: 'quantity')->setValue(
                        objectOrValue: $item,
                        value: new CartItemQuantity($cartItem['quantity']),
                    );

                    $itemReflection->getProperty(name: 'price')->setValue(
                        objectOrValue: $item,
                        value: Money::fromAmount((float) $cartItem['price']),
                    );

                    return $item;
                },
                array: $items,
            ),
        );

        $cartReflection->getProperty('items')->setValue(
            objectOrValue: $cart,
            value: $cartItems
        );

        return $cart;
    }
}
