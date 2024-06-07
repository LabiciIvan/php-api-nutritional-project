<?php

namespace App\Tests\Unit;

use App\Classes\Request;
use PHPUnit\Framework\TestCase;

class RequestTests extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $requestMethod = 'GET';

        $requestURL = '/home/?id=1&name=test';

        $this->request = new Request($requestMethod, $requestURL);
    }
    
    public function testGetMethod(): void
    {
        $httpMethod = $this->request->getMethod();

        $this->assertSame('GET', $httpMethod);
    }

    public function testGetEndpoint(): void
    {
        $httpEndpoint = $this->request->getEndpoint();

        $this->assertSame('/home/', $httpEndpoint);
    }

    public function testGetParameters(): void
    {
        $httpParameters = $this->request->getParameters();

        $this->assertIsArray($httpParameters);

        $this->assertArrayHasKey('id', $httpParameters);

        $this->assertArrayHasKey('name', $httpParameters);
    }

    public function testIfNullReturnedIfNoParameters(): void
    {
        $request = new Request('GET', '/home/');

        $httpParameters = $request->getParameters();

        $this->assertNull($httpParameters);
    }

    public function testRootEndpoint(): void
    {
        $request = new Request('GET', '/');

        $httpEndpoint = $request->getEndpoint();

        $this->assertSame('/', $httpEndpoint);
    }
}
