<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Calibration;
use PHPSurvex\PHPSurvex\Commands\CalibrationCollection;
use PHPSurvex\PHPSurvex\Parser\Line;

class CalibrationConverter
{
    public function convert(Line $line)
    {
        $calibrations = new CalibrationCollection();
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
