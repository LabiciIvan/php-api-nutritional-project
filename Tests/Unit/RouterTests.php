<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Classes\Router;
use PHPUnit\Framework\TestCase;

class RouterTests extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testNullWhenNoRouteIsRegistered(): void
    {
        $routes = $this->router->getRoutes();

        $this->assertNull($routes);
    }

    public function testRegisterGetRoute(): void
    {
        $getRoute = '/nutrition/endpoint/';

        $this->router->get($getRoute, function() use($getRoute) {
            echo 'This is' . $getRoute;
        });

        $routes = $this->router->getRoutes();

        $this->assertIsArray($routes);

        $this->assertArrayHasKey('GET', $routes);

        $this->assertArrayHasKey($getRoute, $routes['GET']);
    }

    public function testRegisterPostRoute(): void
    {
        $postRoute = '/nutrition/endpoint/';

        $this->router->post($postRoute, function() use($postRoute) {
            echo 'This is' . $postRoute;
        });

        $routes = $this->router->getRoutes();

        $this->assertIsArray($routes);

        $this->assertArrayHasKey('POST', $routes);

        $this->assertArrayHasKey($postRoute, $routes['POST']);
    }

    public function testRegisterPutRoute(): void
    {
        $putRoute = '/nutrition/endpoint/';

        $this->router->put($putRoute, function() use($putRoute) {
            echo 'This is' . $putRoute;
        });

        $routes = $this->router->getRoutes();

        $this->assertIsArray($routes);

        $this->assertArrayHasKey('PUT', $routes);

        $this->assertArrayHasKey($putRoute, $routes['PUT']);
    }

    public function testRegisterPatchRoute(): void
    {
        $patchRoute = '/nutrition/endpoint/';

        $this->router->patch($patchRoute, function() use($patchRoute) {
            echo 'This is' . $patchRoute;
        });

        $routes = $this->router->getRoutes();

        $this->assertIsArray($routes);

        $this->assertArrayHasKey('PATCH', $routes);

        $this->assertArrayHasKey($patchRoute, $routes['PATCH']);
    }

    public function testRegisterDeleteRoute(): void
    {
        $deleteRoute = '/nutrition/endpoint/';

        $this->router->delete($deleteRoute, function() use($deleteRoute) {
            echo 'This is' . $deleteRoute;
        });

        $routes = $this->router->getRoutes();

        $this->assertIsArray($routes);

        $this->assertArrayHasKey('DELETE', $routes);

        $this->assertArrayHasKey($deleteRoute, $routes['DELETE']);
    }
}
