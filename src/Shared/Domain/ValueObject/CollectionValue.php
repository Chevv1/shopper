<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use ArrayIterator;
use Traversable;

abstract readonly class CollectionValue implements \IteratorAggregate, \Countable
{
    /**
     * @param object[] $items
     */
    public function __construct(
        protected array $items = [],
    ) {
        foreach ($items as $item) {
            $this->validateItem($item);
        }
    }

    private function validateItem(object $item): void
    {
        $itemType = static::itemType();

        if (is_a(object_or_class: $item, class: $itemType) === false) {
            throw new \InvalidArgumentException(
                message: sprintf('Item must be instance of %s', $itemType),
            );
        }
    }

    abstract protected static function itemType(): string;

    final public function count(): int
    {
        return count($this->items);
    }

    final public function isEmpty(): bool
    {
        return empty($this->items);
    }

    final public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    final public function toArray(): array
    {
        return $this->items;
    }

    final public function map(callable $callback): array
    {
        return array_map(
            callback: $callback,
            array: $this->items,
        );
    }

    final public function reduce(callable $callback, ?object $initial = null): ?object
    {
        return array_reduce(
            array: $this->items,
            callback: $callback,
            initial: $initial,
        );
    }

    final public function filter(callable $callback): static
    {
        return new static(array_filter($this->items, $callback));
    }

    final public function add(object $item): static
    {
        return new static([...$this->items, $item]);
    }
}
