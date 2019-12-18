<?php

namespace Dartui\Survex\Commands;

use Dartui\Survex\Commands\Calibration;
use Dartui\Survex\Parser\Line;
use Dartui\Survex\Support\Collection;

class CalibrationCollection extends Collection
{
    public static function fromLine(Line $line)
    {
        $calibrations = new static();
        $quantities   = [];

        $zeroError        = null;
        $zeroErrorPattern = '/^[+-]?\d+(?:\.\d+)?$/';

        $units = null;
        $scale = null;

        foreach ($line->getData()->getValues() as $value) {
            if ($zeroError === null && preg_match($zeroErrorPattern, $value) === 1) {
                $zeroError = $value;
            } elseif ($zeroError === null) {
                $quantities[] = $value;
            } elseif (is_numeric($value)) {
                $scale = $value;
            } else {
                $units = $value;
            }
        }

        if ($zeroError === null) {
            throw new \Exception('missing zero error');
        }

        foreach ($quantities as $quantity) {
            $calibrations->append(
                new Calibration($quantity, $zeroError, $units, $scale)
            );
        }

        return $calibrations;
    }
}
