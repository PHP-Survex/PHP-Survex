<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Unit;
use PHPSurvex\PHPSurvex\Commands\UnitCollection;
use PHPSurvex\PHPSurvex\Parser\Line;
use PHPSurvex\PHPSurvex\Support\Collection;

class UnitConverter
{
    public function convert(Line $line)
    {
        $unitCollection = new UnitCollection();
        $quantities     = [];

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
            $unitCollection->append(
                new Unit($quantity, $units, $factor)
            );
        }

        return $unitCollection;
    }
}
