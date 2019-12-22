<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Date;
use PHPSurvex\PHPSurvex\Commands\DateRange;
use PHPSurvex\PHPSurvex\Parser\Line;

class DateConverter
{
    public function convert(Line $line)
    {
        preg_match($this->pattern(), $line->getData()->getContent(), $matches);

        $start = new Date(
            $matches[1],
            isset($matches[2]) ? $matches[2] : null,
            isset($matches[3]) ? $matches[3] : null
        );

        if (!isset($matches[4])) {
            return $start;
        }

        $end = new Date(
            $matches[4],
            isset($matches[5]) ? $matches[5] : null,
            isset($matches[6]) ? $matches[6] : null
        );

        return DateRange($start, $end);
    }

    public function pattern()
    {
        return '/^(\d{4})(?:\.(\d{2})(?:\.(\d{2}))?)?(?:\-(\d{4})(?:\.(\d{2})(?:\.(\d{2}))?)?)?$/';
    }
}
