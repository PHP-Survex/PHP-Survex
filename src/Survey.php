<?php

namespace Dartui\Survex;

use Dartui\Survex\Commands\Calibration;
use Dartui\Survex\Commands\CalibrationCollection;
use Dartui\Survex\Commands\Team;
use Dartui\Survex\Commands\TeamCollection;
use Dartui\Survex\Commands\Unit;
use Dartui\Survex\Commands\UnitCollection;
use Dartui\Survex\Converters\TeamConverter;
use Dartui\Survex\Converters\UnitConverter;
use Dartui\Survex\Parser\LineCollection;

class Survey
{
    protected $name;

    protected $title;

    protected $calibrations;

    protected $units;

    protected $team;

    final public function __construct()
    {
        $this->calibrations = new CalibrationCollection();
        $this->units        = new UnitCollection();
        $this->team         = new TeamCollection();
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
                    $survey->addDate();
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

    public function getUnits()
    {
        return $this->units;
    }
}
