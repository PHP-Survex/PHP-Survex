<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Measurement;
use PHPSurvex\PHPSurvex\Parser\Line;

class MeasurementConverter
{
    const SEPARATOR = '[SEP]';

    public function convert(Line $line)
    {
        $content = preg_replace('/\s+/', static::SEPARATOR, $line->getContent());
        $values  = explode(static::SEPARATOR, $content);

        return new Measurement($values);
    }
}
