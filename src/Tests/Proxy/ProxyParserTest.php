<?php

namespace Glooby\HttpClientBundle\Tests\Proxy;

use Glooby\HttpClientBundle\Proxy\ProxyParser;

/**
 * @author Emil Kilhage
 */
class ProxyParserTest extends \PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $parser = new ProxyParser();

        $string = 'socks5://127.0.0.1:1080';

        $proxy = $parser->parse($string);

        $this->assertEquals('socks5', $proxy->getScheme());
        $this->assertEquals('127.0.0.1', $proxy->getHost());
        $this->assertEquals('1080', $proxy->getPort());
        $this->assertNull($proxy->getUser());
        $this->assertNull($proxy->getPassword());

        $this->assertEquals($string, $proxy->getUrl());
    }

    public function test2()
    {
        $parser = new ProxyParser();

        $string = 'socks5://glooby@127.0.0.1:1080';

        $proxy = $parser->parse($string);

        $this->assertEquals('socks5', $proxy->getScheme());
        $this->assertEquals('127.0.0.1', $proxy->getHost());
        $this->assertEquals('1080', $proxy->getPort());
        $this->assertEquals('glooby', $proxy->getUser());
        $this->assertNull($proxy->getPassword());

        $this->assertEquals($string, $proxy->getUrl());
    }

    public function test3()
    {
        $parser = new ProxyParser();

        $string = 'socks5://glooby:glooby123@127.0.0.1:1080';

        $proxy = $parser->parse($string);

        $this->assertEquals('socks5', $proxy->getScheme());
        $this->assertEquals('127.0.0.1', $proxy->getHost());
        $this->assertEquals('1080', $proxy->getPort());
        $this->assertEquals('glooby', $proxy->getUser());
        $this->assertEquals('glooby123', $proxy->getPassword());

        $this->assertEquals($string, $proxy->getUrl());
    }
}
