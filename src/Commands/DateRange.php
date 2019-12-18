<?php

namespace Dartui\Survex\Commands;

class DateRange
{
    protected $start;

    protected $end;

    public function __construct(Date $start, Date $end)
    {
        $this->setStart($start);
        $this->setEnd($end);
    }

    public function setStart(Date $start)
    {
        $this->start = $start;

        return $this;
    }

    public function setEnd(Date $end)
    {
        $this->end = $end;

        return $this;
    }
}
