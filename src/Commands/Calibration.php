<?php

namespace Dartui\Survex\Commands;

class Calibration
{
    protected $quantity;

    protected $zeroError;

    protected $units;

    protected $scale;

    public function __construct($quantity, $zeroError, $units = null, $scale = null)
    {
        $this->setQuantity($quantity);
        $this->setZeroError($zeroError);
        $this->setUnits($units);
        $this->setScale($scale);
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setZeroError($zeroError)
    {
        $this->zeroError = (float) $zeroError;

        return $this;
    }

    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    public function setScale($scale)
    {
        $this->scale = $scale !== null ? (float) $scale : 1.0;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getZeroError()
    {
        return $this->zeroError;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function getScale()
    {
        return $this->scale;
    }

    public function calibrate($reading, $precision = null)
    {
        if (!is_numeric($reading)) {
            throw new \Exception('bad reading value');
        }

        return ($reading * $this->getScale()) - $this->getZeroError();
    }
}
