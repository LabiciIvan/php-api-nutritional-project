<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private ?array $routes = null;

    public function get(string $path, callable|array $callbackOrArray): void
    {
        $this->routes['GET'][$path] = $callbackOrArray;
    }

    public function post(string $path, callable|array $callbackOrArray): void
    {
        $this->routes['POST'][$path] = $callbackOrArray;
    }

    public function put(string $path, callable|array $callbackOrArray): void
    {
        $this->routes['PUT'][$path] = $callbackOrArray;
    }

    public function patch(string $path, callable|array $callbackOrArray): void
    {
        $this->routes['PATCH'][$path] = $callbackOrArray;
    }

    public function delete(string $path, callable|array $callbackOrArray): void
    {
        $this->routes['DELETE'][$path] = $callbackOrArray;
    }

    public function getRoutes(): ?array
    {
        return $this->routes;
    }
}
