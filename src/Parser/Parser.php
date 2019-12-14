<?php

namespace Dartui\Survex\Parser;

use Dartui\Survex\Parser\Line;
use Dartui\Survex\Parser\LineCollection;
use Dartui\Survex\Parser\LineStack;
use Dartui\Survex\Survey;
use Dartui\Survex\SurveyCollection;

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
            $collection->append(Survey::fromLines($lines));
        });

        return $collection;
    }
}
