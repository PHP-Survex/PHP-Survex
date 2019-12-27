<?php

namespace PHPSurvex\PHPSurvex\Parser;

use PHPSurvex\PHPSurvex\Parser\Line;
use PHPSurvex\PHPSurvex\Parser\LineCollection;
use PHPSurvex\PHPSurvex\Parser\LineStack;
use PHPSurvex\PHPSurvex\Survey;
use PHPSurvex\PHPSurvex\SurveyCollection;

class Parser
{
    protected $content;

    protected $lines;

    final public function __construct($content)
    {
        $this->content = $content;
    }

    public static function make($content)
    {
        return new static($content);
    }

    public function parse()
    {
        $lines   = $this->extractLines();
        $lines   = $this->parseLines($lines);
        $surveys = $this->extractSurveys($lines);
        $surveys = $this->parseSurveys($surveys);

        return $surveys;
    }

    public function extractLines()
    {
        $lines = explode("\n", $this->content);
        $lines = array_filter($lines, function ($line) {
            return strlen(trim($line)) > 0;
        });

        return array_values($lines);
    }

    public function parseLines(array $lines = [])
    {
        $lineCollection = new LineCollection();

        foreach ($lines as $line) {
            $lineCollection->append((new Line($line))->parse());
        }

        return $lineCollection;
    }

    public function extractSurveys(LineCollection $lines)
    {
        return LineStack::fromLines($lines);
    }

    public function parseSurveys(LineStack $stack)
    {
        $collection = new SurveyCollection();

        $stack->each(function ($lines) use ($collection) {
            $collection->append(new Survey($lines));
        });

        return $collection;
    }
}
