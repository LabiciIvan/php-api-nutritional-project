<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\ApplicationInterface;

class Application implements ApplicationInterface
{
    private Request $request;

    private Router $router;

    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;

        $this->router = $router;
    }

    public function run(): void
    {
        $endpoint = $this->request->getEndpoint();

        $method = $this->request->getMethod();

        $routes = $this->router->getRoutes();

        if (!$method || !$endpoint) {
            http_response_code(500);
            echo 'System is slowed down, try again later.';
            exit;
        }

        if (!isset($routes[$method][$endpoint])) {
            http_response_code(404);
            echo 'Request resource not found.';
            exit;
        }

        $callbackOrArray = $routes[$method][$endpoint];

        if (is_callable($callbackOrArray)) {
            call_user_func($callbackOrArray);
        } else if (is_array($callbackOrArray)) {
            $class = $callbackOrArray[0];

            $method = $callbackOrArray[1];
        }
    }
}
