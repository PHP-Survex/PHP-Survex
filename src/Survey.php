<?php

namespace PHPSurvex\PHPSurvex;

use PHPSurvex\PHPSurvex\Commands\Calibration;
use PHPSurvex\PHPSurvex\Commands\CalibrationCollection;
use PHPSurvex\PHPSurvex\Commands\Data;
use PHPSurvex\PHPSurvex\Commands\DataCollection;
use PHPSurvex\PHPSurvex\Commands\Date;
use PHPSurvex\PHPSurvex\Commands\DateCollection;
use PHPSurvex\PHPSurvex\Commands\DateRange;
use PHPSurvex\PHPSurvex\Commands\Team;
use PHPSurvex\PHPSurvex\Commands\TeamCollection;
use PHPSurvex\PHPSurvex\Commands\Unit;
use PHPSurvex\PHPSurvex\Commands\UnitCollection;
use PHPSurvex\PHPSurvex\Converters\CalibrationConverter;
use PHPSurvex\PHPSurvex\Converters\DataConverter;
use PHPSurvex\PHPSurvex\Converters\DateConverter;
use PHPSurvex\PHPSurvex\Converters\MeasurementConverter;
use PHPSurvex\PHPSurvex\Converters\TeamConverter;
use PHPSurvex\PHPSurvex\Converters\UnitConverter;
use PHPSurvex\PHPSurvex\Enums\LineType;
use PHPSurvex\PHPSurvex\Parser\Line;
use PHPSurvex\PHPSurvex\Parser\LineCollection;

class Survey
{
    protected $name;

    protected $title;

    protected $calibrations;

    protected $units;

    protected $team;

    protected $dates;

    protected $data;

    final public function __construct(LineCollection $lines = null)
    {
        $this->lines = $lines;

        $this->calibrations = new CalibrationCollection();
        $this->units        = new UnitCollection();
        $this->team         = new TeamCollection();
        $this->dates        = new DateCollection();
        $this->data         = new DataCollection();

        $this->convertLines();
    }

    public function convertLines()
    {
        $data = new Data();

        foreach ($this->lines as $line) {
            $lineType = $line->getType();

            /**
             * For now skip all comments.
             */
            if ($lineType === LineType::COMMENT) {
                continue;
            }

            $value = $this->convertLine($line);

            if ($value instanceof Data) {
                $data = $value;
            }

            if ($lineType === LineType::INFORMATION) {
                $this->addInformation($line->getTitle(), $value);
            } elseif ($lineType === LineType::MEASUREMENT) {
                $data->addMeasurement($value);
            }
        }

        if (!$this->getData()->contains($data)) {
            $this->addData($data);
        }
    }

    public function convertLine(Line $line)
    {
        if ($line->getType() === LineType::MEASUREMENT) {
            return (new MeasurementConverter())->convert($line);
        }

        switch ($line->getTitle()) {
            case 'begin':
                return $line->getData()->getContent();
            case 'title':
                return trim($line->getData()->getContent(), '\"');
            case 'calibrate':
                return (new CalibrationConverter())->convert($line);
            case 'units':
                return (new UnitConverter())->convert($line);
            case 'team':
                return (new TeamConverter())->convert($line);
            case 'date':
                return (new DateConverter())->convert($line);
            case 'data':
                return (new DataConverter())->convert($line);
        }
    }

    public function addInformation($lineTitle, $value)
    {
        switch ($lineTitle) {
            case 'begin':
                return $this->setName($value);
            case 'title':
                return $this->setTitle($value);
            case 'calibrate':
                return $this->addCalibrations($value);
            case 'units':
                return $this->addUnits($value);
            case 'team':
                return $this->addTeam($value);
            case 'date':
                if ($value instanceof DateRange) {
                    return $this->addDateRange($value);
                } elseif ($value instanceof Date) {
                    return $this->addDate($value);
                }
            case 'data':
                return $this->addData($value);
        }
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

        return $this;
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

        return $this;
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

    public function addData(Data $data)
    {
        $this->data->append($data);

        return $this;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function getData()
    {
        return $this->data;
    }
}
