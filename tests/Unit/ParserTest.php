<?php

namespace PHPSurvex\PHPSurvex\Tests\Unit;

use PHPSurvex\PHPSurvex\Parser\Parser;
use PHPSurvex\PHPSurvex\Tests\TestCase;

class ParserTest extends TestCase
{
    // public function testCanInitializeByConstructor()
    // {
    //     $parser = new Parser('test');

    //     $this->assertInstanceOf(Parser::class, $parser);
    // }

    // public function testCanInitializeByStaticMethod()
    // {
    //     $parser = Parser::make('test');

    //     $this->assertInstanceOf(Parser::class, $parser);
    // }

    public function testCanParseBaseFile()
    {
        $filename = __DIR__ . '/../Examples/dupce.svx';
        $content  = file_get_contents($filename);
        $parser   = Parser::make($content);

        $surveys = $parser->parse();
        dump($surveys->first());
    }
}
