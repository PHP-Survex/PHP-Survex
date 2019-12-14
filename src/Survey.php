<?php

namespace Dartui\Survex;

use Dartui\Survex\Parser\LineCollection;
use Dartui\Survex\Survey\Calibration\Calibration;
use Dartui\Survex\Survey\Calibration\CalibrationCollection;
use Dartui\Survex\Survey\Unit\Unit;
use Dartui\Survex\Survey\Unit\UnitCollection;

class Survey
{
    protected $name;

    protected $title;

    protected $calibrations;

    protected $units;

    final public function __construct()
    {
        $this->calibrations = new CalibrationCollection();
        $this->units        = new UnitCollection();
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
                        UnitCollection::fromLine($line)
                    );
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

    public function getUnits()
    {
        return $this->units;
    }
}
