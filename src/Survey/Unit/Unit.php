<?php

namespace Dartui\Survex\Survey\Unit;

/**
 * @todo validation
 */
class Unit
{
    protected $quantity;

    protected $units;

    protected $factor;

    public function __construct($quantity, $units = null, $factor = null)
    {
        $this->setQuantity($quantity);
        $this->setUnits($units);
        $this->setFactor($factor);
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    public function setFactor($factor)
    {
        $this->factor = $factor !== null ? (float) $factor : 1.0;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function getFactor()
    {
        return $this->factor;
    }
}
