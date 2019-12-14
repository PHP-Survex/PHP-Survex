<?php

namespace Dartui\Survex\Tests\Unit;

use Dartui\Survex\Parser\Parser;
use Dartui\Survex\Tests\TestCase;

class ParserTest extends TestCase
{
    public function testCanInitializeByConstructor()
    {
        $parser = new Parser('test');

        $this->assertInstanceOf(Parser::class, $parser);
    }

    public function testCanInitializeByStaticMethod()
    {
        $parser = Parser::make('test');

        $this->assertInstanceOf(Parser::class, $parser);
    }

    public function testCanParseBaseFile()
    {
        $filename = __DIR__ . '/../Examples/dupce.svx';
        $content  = file_get_contents($filename);
        $parser   = Parser::make($content);

        $surveys = $parser->parse();
    }
}
