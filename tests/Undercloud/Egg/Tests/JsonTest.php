<?php
namespace Undercloud\Egg\Tests;

use PHPUnit_Framework_TestCase;
use Undercloud\Egg\JsonDecoder;
use Undercloud\Egg\JsonEncoder;

class JsonTest extends PHPUnit_Framework_TestCase
{
	public function testSimpleEncode()
    {
        $json = new JsonEncoder;

        $this->assertEquals(
            $json->encode(null),
            'null'
        );

        $this->assertEquals(
            $json->encode(false),
            'false'
        );

        $this->assertEquals(
            $json->encode(new \stdClass),
            '{}'
        );

        $this->assertEquals(
            $json->encode([]),
            '[]'
        );

        $this->assertEquals(
            $json->encode([5,9,3]),
            '[5,9,3]'
        );

        $this->assertEquals(
            $json->encode(['foo' => 'bar']),
            '{"foo":"bar"}'
        );
    }

    public function testSimpleDecode()
    {
        $json = new JsonDecoder;

        $this->assertEquals(
            $json->decode('null'),
            null
        );

        $this->assertEquals(
            $json->decode('false'),
            false
        );

        $this->assertEquals(
            $json->decode('{}'),
            new \stdClass
        );

        $this->assertEquals(
            $json->decode('[]'),
            []
        );

        $this->assertEquals(
            $json->decode('[5,9,3]'),
            [5,9,3]
        );
    }

    public function testSimpleDecodeAssoc()
    {
        $json = new JsonDecoder;

        $this->assertEquals(
            $json->asAssoc()->decode('{"foo":"bar"}'),
            ['foo' => 'bar']
        );

        $json = new JsonDecoder;

        $this->assertEquals(
            $json->objectAsArray()->decode('{"foo":"bar"}'),
            ['foo' => 'bar']
        );
    }
}