<?php

namespace Src\Infrastructure\Database;

use ArrayAccess;
use IteratorAggregate;
use Countable;
use ArrayIterator;

class Collection implements IteratorAggregate, ArrayAccess, Countable
{
    public function __construct(
        private array $items = []
    ) {}

    public function all(): array
    {
        return $this->items;
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    public function toArray(): array
    {
        return array_map(fn ($i) =>
            $i instanceof Model ? $i->toArray() : $i,
            $this->items
        );
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}