<?php

namespace PHPSurvex\PHPSurvex;

use PHPSurvex\PHPSurvex\Commands\Calibration;
use PHPSurvex\PHPSurvex\Commands\CalibrationCollection;
use PHPSurvex\PHPSurvex\Commands\Date;
use PHPSurvex\PHPSurvex\Commands\DateCollection;
use PHPSurvex\PHPSurvex\Commands\DateRange;
use PHPSurvex\PHPSurvex\Commands\Team;
use PHPSurvex\PHPSurvex\Commands\TeamCollection;
use PHPSurvex\PHPSurvex\Commands\Unit;
use PHPSurvex\PHPSurvex\Commands\UnitCollection;
use PHPSurvex\PHPSurvex\Converters\DateConverter;
use PHPSurvex\PHPSurvex\Converters\TeamConverter;
use PHPSurvex\PHPSurvex\Converters\UnitConverter;
use PHPSurvex\PHPSurvex\Parser\LineCollection;

class Survey
{
    protected $name;

    protected $title;

    protected $calibrations;

    protected $units;

    protected $team;

    protected $dates;

    final public function __construct()
    {
        $this->calibrations = new CalibrationCollection();
        $this->units        = new UnitCollection();
        $this->team         = new TeamCollection();
        $this->dates        = new DateCollection();
    }

    public static function fromLines(LineCollection $lines)
    {
        $survey = new static();

        foreach ($lines as $line) {
            switch ($line->getTitle()) {
                case 'begin':
                    $survey->setName(
                        $line->getData()->getContent()
                    );
                    break;
                case 'title':
                    $survey->setTitle(
                        trim($line->getData()->getContent(), '\"')
                    );
                    break;
                case 'calibrate':
                    $survey->addCalibrations(
                        CalibrationCollection::fromLine($line)
                    );
                    break;
                case 'units':
                    $survey->addUnits(
                        (new UnitConverter())->convert($line)
                    );
                    break;
                case 'team':
                    $survey->addTeam(
                        (new TeamConverter())->convert($line)
                    );
                    break;
                case 'date':
                    $date = (new DateConverter())->convert($line);

                    if ($date instanceof DateRange) {
                        $survey->addDateRange($date);
                    } elseif ($date instanceof Date) {
                        $survey->addDate($date);
                    }
                    break;
            }
        }

        return $survey;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function addCalibrations(CalibrationCollection $calibrations)
    {
        $calibrations->each(function ($calibration) {
            $this->addCalibration($calibration);
        });
    }

    public function addCalibration(Calibration $calibration)
    {
        $this->calibrations->append($calibration);

        return $this;
    }

    public function addUnits(UnitCollection $unit)
    {
        $unit->each(function ($unit) {
            $this->addUnit($unit);
        });
    }

    public function addUnit(Unit $unit)
    {
        $this->units->append($unit);

        return $this;
    }

    public function addTeam(Team $team)
    {
        $this->team->append($team);

        return $this;
    }

    public function addDate(Date $date)
    {
        $this->dates->append($date);

        return $this;
    }

    public function addDateRange(DateRange $dateRange)
    {
        $this->dates->append($dateRange);

        return $this;
    }

    public function getUnits()
    {
        return $this->units;
    }
}
