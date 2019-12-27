<?php

namespace PHPSurvex\PHPSurvex\Parser;

use PHPSurvex\PHPSurvex\Enums\LineType;

class Line
{
    protected $content;

    protected $title;

    protected $data;

    protected $comment;

    protected $type = LineType::INFORMATION;

    final public function __construct($content, $title = null, $data = null, $comment = null)
    {
        $this->setContent($content);
        $this->setTitle($title);
        $this->setData($data);
        $this->setComment($comment);
    }

    public static function commentLine($comment)
    {
        return (new static('', null, null, $comment))->setType(LineType::COMMENT);
    }

    public static function measurementLine($content, $comment = null)
    {
        return (new static($content, null, null, $comment))->setType(LineType::MEASUREMENT);
    }

    public static function informationLine($content, $title = null, $data = null, $comment = null)
    {
        return (new static($content, $title, $data, $comment))
            ->setType(LineType::INFORMATION);
    }

    public function parse()
    {
        if ($this->content[0] === ';') {
            return static::commentLine(substr($this->content, 1));
        }

        preg_match($this->linePattern(), $this->content, $matches);

        if (count($matches) === 0) {
            $parts = explode(';', $this->content);

            return static::measurementLine($parts[0], isset($parts[1]) ? $parts[1] : null);
        }

        return static::informationLine(
            $this->content,
            isset($matches['title']) ? $matches['title'] : null,
            isset($matches['data']) ? $matches['data'] : null,
            isset($matches['comment']) ? $matches['comment'] : null
        );
    }

    public function setContent($content)
    {
        $this->content = trim($content);

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = strtolower($title);

        return $this;
    }

    public function setData($data)
    {
        $this->data = (new LineData($data))->parse();

        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = trim($comment);

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getType()
    {
        return $this->type;
    }

    protected function linePattern()
    {
        return '/^\*(?P<title>.+?)(?:\s+(?P<data>.+?))?(?:\s*\;\s*(?P<comment>.*))?$/';
    }
}
