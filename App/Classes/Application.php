<?php

declare(strict_types=1);

namespace App\Classes;

use App\Utilities\Json;
use App\Utilities\Response;
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
            Response::sendResponse(Json::toJson(['data' => 'System is slowed down, try again later.']), 500);
        }

        if (!isset($routes[$method][$endpoint])) {
            Response::sendResponse(Json::toJson(['data' => 'Request resource not found.']), 404);
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
