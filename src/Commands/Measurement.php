<?php

namespace PHPSurvex\PHPSurvex\Commands;

class Measurement
{
    protected $values;

    protected $data;

    public function __construct(array $values)
    {
        $this->setValues($values);
    }

    public function setData(Data $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getValue($ordering)
    {
        $key = array_search($ordering, $this->getData()->getOrdering());

        if ($key === null) {
            throw new Exception('Key cannot be null');
        }

        return $this->getValues()[$key];
    }
}
