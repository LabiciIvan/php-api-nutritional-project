<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Classes\Kernel;
use App\Classes\Router;
use App\Classes\Request;
use App\Classes\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTests extends TestCase
{
    private Request $request;

    private Router $router;

    private Kernel $kernel;

    public function setUp(): void
    {
        // Load $router instance
        require_once __DIR__ . '/../../App/routes.php';

        $this->request = new Request('GET', '/nutrition/application/');

        $this->router = $router;

        $this->kernel = new Kernel($this->request, $this->router);
    }
    
    public function testRunMethodRunsTheApplication(): void
    {
        $applicationInstance = new Application($this->kernel);

        $applicationInstance->run();

        $this->assertTrue(true);
    }
}
