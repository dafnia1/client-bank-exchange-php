<?php

namespace Tests\Kily\Tools1C\Tests\ClientBankExchange;

use Kily\Tools1C\ClientBankExchange\Parser;
use PHPUnit\Framework\TestCase;
use Kily\Tools1C\ClientBankExchange\Exception;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-04-10 at 17:43:47.
 */
class ParserTest extends TestCase
{
    protected $object = null;

    protected function setUp(): void
    {
        $dp_item = $this->goodFileProvider()[0];
        $file = $dp_item[0];

        $this->object = new Parser($file);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::__construct
     * @dataProvider goodFileProvider
     */
    public function test__constructGood($file, $encoding, $count)
    {
        $parser = new Parser($file, $encoding);
        $this->assertCount($count, $parser->documents);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::__construct
     * @dataProvider badFileProvider
     */
    public function test__constructBad($file)
    {
        $this->expectException(Exception::class);
        $parser = new Parser($file, 'cp1251');
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::parse_file
     */
    public function testParse_file()
    {
        $dp_item = $this->goodFileProvider()[0];
        $file = $dp_item[0];
        $this->object->parse_file($file);
        $this->assertNotEmpty($this->object->documents);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::parse
     */
    public function testParse()
    {
        $dp_item = $this->goodFileProvider()[0];
        $file = $dp_item[0];
        $this->object->parse(file_get_contents($file));
        $this->assertNotEmpty($this->object->documents);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::general
     */
    public function testGeneral()
    {
        $this->assertEquals('Windows', $this->object->general->{'Кодировка'});
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::filter
     */
    public function testFilter()
    {
        $this->assertEquals('2016-01-11', $this->object->filter->{'ДатаНачала'});
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::remainings
     */
    public function testRemainings()
    {
        $this->assertEquals(45329.910000000003, $this->object->remainings->{'НачальныйОстаток'});
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::documents
     */
    public function testDocuments()
    {
        $this->assertNotEmpty($this->object->documents);
        $this->assertEquals(697162, $this->object->documents[0]->{'Номер'});
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::offsetSet
     */
    public function testOffsetSet()
    {
        $this->object['general'] = 123;
        $this->assertEquals(123, $this->object['general']);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::offsetExists
     */
    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->object['general']));
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::offsetUnset
     */
    public function testOffsetUnset()
    {
        unset($this->object['general']);
        $this->assertEquals($this->object['general'], null);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::offsetGet
     */
    public function testOffsetGet()
    {
        $this->assertNotEmpty($this->object['general']);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::__get
     */
    public function test__get()
    {
        $this->assertNotEmpty($this->object->general);
    }

    /**
     * @covers Kily\Tools1C\ClientBankExchange\Parser::toArray()
     */
    public function testToArray()
    {
        $arr = $this->object->toArray();
        $this->assertArrayHasKey('remainings', $arr);
        $this->assertArrayHasKey('НачальныйОстаток', $arr['remainings']);
        $this->assertArrayHasKey('documents', $arr);
        $this->assertArrayHasKey(0, $arr['documents']);
        $this->assertArrayHasKey('Номер', $arr['documents'][0]);
    }

    public function goodFileProvider()
    {
        $path = dirname(dirname(__DIR__)).'/resources';

        return [
            [$path.'/one_day.txt', 'cp1251', 13],
            [$path.'/one_day_utf8.txt', 'utf-8', 13],
            [$path.'/huge.txt', 'cp1251', 9783],
        ];
    }

    public function badFileProvider()
    {
        $path = dirname(dirname(__DIR__)).'/resources';

        return [
            ['/nonexistent'],
        ];
    }
}
