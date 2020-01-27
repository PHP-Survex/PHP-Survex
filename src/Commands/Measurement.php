<?php

namespace PHPSurvex\PHPSurvex\Commands;

class Measurement
{
    protected $values;

    protected $data;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function setData(Data $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }
}
