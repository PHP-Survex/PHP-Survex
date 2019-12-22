<?php

namespace PHPSurvex\PHPSurvex\Commands;

class Date
{
    protected $year;

    protected $month;

    protected $day;

    public function __construct($year, $month = null, $day = null)
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setDay($day);
    }

    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }
}
