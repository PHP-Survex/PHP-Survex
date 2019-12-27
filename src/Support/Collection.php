<?php

namespace PHPSurvex\PHPSurvex\Support;

use ArrayIterator;
use IteratorAggregate;

class Collection implements IteratorAggregate
{
    protected $items;

    final public function __construct($items = [])
    {
        $this->items = $this->getItems($items);
    }

    public static function make($items = [])
    {
        return new static($items);
    }

    public function all()
    {
        return $this->items;
    }

    public function push($value)
    {
        $this->items[] = $value;

        return $this;
    }

    public function append($value)
    {
        return $this->push($value);
    }

    public function prepend($value)
    {
        array_unshift($this->items, $value);

        return $this;
    }

    public function put($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function forget($key)
    {
        unset($this->items[$key]);

        return $this;
    }

    public function first(callable $callback = null)
    {
        foreach ($this->items as $item) {
            if (is_callable($callback) && $callback($item)) {
                return $item;
            }

            return $item;
        }
    }

    public function toArray()
    {
        $output = [];

        foreach ($this->items as $key => $item) {
            if ($item instanceof self) {
                $item = $item->toArray();
            }

            $output[$key] = $item;
        }

        return $output;
    }

    public function map(callable $callback)
    {
        $keys  = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    public function contains($value)
    {
        return in_array($value, $this->items);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    protected function getItems($items)
    {
        if ($items instanceof self) {
            return $items->toArray();
        } elseif (is_array($items)) {
            return $items;
        }

        return (array) $items;
    }
}
