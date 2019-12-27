<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Data;
use PHPSurvex\PHPSurvex\Parser\Line;

class DataConverter
{
    public function convert(Line $line)
    {
        $values = $line->getData()->getValues();

        $style    = array_shift($values);
        $ordering = $values;

        return new Data($style, $ordering);
    }
}
