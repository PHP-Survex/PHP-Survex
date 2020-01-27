<?php

namespace PHPSurvex\PHPSurvex\Commands;

class Data
{
    protected $style;

    protected $ordering;

    protected $measurements;

    public function __construct($style = null, array $ordering = null)
    {
        $this->setStyle($style);
        $this->setOrdering($ordering);

        $this->measurements = new MeasurementCollection();
    }

    public function setStyle($style = null)
    {
        $this->style = $style;

        return $this;
    }

    public function getStyle()
    {
        return $this->style ?: 'normal';
    }

    public function getMeasurements()
    {
        return $this->measurements;
    }

    public function setOrdering(array $ordering = null)
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getOrdering()
    {
        return $this->ordering ?: ['from', 'to', 'compass', 'tape', 'clino'];
    }

    public function addMeasurement(Measurement $measurement)
    {
        $measurement->setData($this);

        $this->measurements->append($measurement);

        return $this;
    }
}
