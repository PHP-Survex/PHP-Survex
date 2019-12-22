<?php

namespace PHPSurvex\PHPSurvex\Parser;

class LineData
{
    protected $content;

    protected $values;

    final public function __construct($content)
    {
        $this->setContent(trim($content));
    }

    public function parse()
    {
        $content = preg_replace('/\s+/', "\t", $this->content);

        $this->setValues(explode("\t", $content));

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getValues()
    {
        return $this->values;
    }
}
