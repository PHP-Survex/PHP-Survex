<?php

namespace Dartui\Survex\Survey\Unit;

use Dartui\Survex\Parser\Line;
use Dartui\Survex\Support\Collection;
use Dartui\Survex\Survey\Unit\Unit;

class UnitCollection extends Collection
{
    public static function fromLine(Line $line)
    {
        $collection = new static();
        $quantities = [];

        $values = Collection::make($line->getData()->getValues())->reverse();

        $units  = null;
        $factor = null;

        foreach ($values as $value) {
            if ($units === null) {
                $units = $value;
            } elseif (is_numeric($value)) {
                $factor = $value;
            } else {
                $quantities[] = $value;
            }
        }

        if ($units === null) {
            throw new \Exception('missing units');
        }

        foreach ($quantities as $quantity) {
            $collection->append(
                new Unit($quantity, $units, $factor)
            );
        }

        return $collection;
    }

    // public function get($key)
    // {
    //     return $this->first(function ($unit) use ($key) {
    //         return $unit->getQuantity() == $key;
    //     });
    // }
}
