<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Classes\Router;
use App\Classes\Request;
use App\Classes\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTests extends TestCase
{
    public function testRunMethodRunsTheApplication(): void
    {
        $request = new Request('GET', '/nutrition/application/');

        $router = new Router();

        $applicationInstance = new Application($request, $router);

        $applicationInstance->run();

        $this->assertTrue(true);
    }
}
