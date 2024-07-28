<?php

declare(strict_types=1);

namespace App\Classes;

use Error;
use RuntimeException;
use App\Classes\Kernel;
use App\Utilities\Json;
use App\Utilities\Response;
use App\Utilities\ErrorLogger;
use App\Utilities\DependencyInjector;
use App\Interfaces\ApplicationInterface;

class Application implements ApplicationInterface
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function run(): void
    {
        $endpoint = $this->kernel->request->getEndpoint();

        $method = $this->kernel->request->getMethod();

        $routes = $this->kernel->router->getRoutes();

        if (!$method || !$endpoint) {
            Response::sendResponse(Json::toJson(['data' => 'System is slowed down, try again later.']), 500);
        }

        if (!isset($routes[$method][$endpoint])) {
            Response::sendResponse(Json::toJson(['data' => 'Request resource not found.']), 404);
        }

        $callbackOrArray = $routes[$method][$endpoint];

        if (is_callable($callbackOrArray)) {
            call_user_func($callbackOrArray);
        } elseif (is_array($callbackOrArray)) {

            $class = $callbackOrArray[0] ?? null;

            $method = $callbackOrArray[1] ?? null;

            if ($class && $method && class_exists($class)) {

                $classInstance = new $class();

                $dependencyInjector = new DependencyInjector($class, $method);

                $dependencies = $dependencyInjector->hasDependencies();

                if ($dependencies) {
                    $instances = array_map(function($class) {
                        return new $class();
                    }, $dependencies);

                    $classInstance->$method(...$instances);
                }

                try {
                    $classInstance->$method();
                } catch (Error $e) {
                    ErrorLogger::logError($e->getMessage(), __DIR__ . '/../../errors.txt');
                    Response::sendResponse(Json::toJson(['data' => 'The system is currently experiencing a malfunction. Please try again later.']), 500);
                }

            } else {
                throw new RuntimeException('Could not load class or method from Router::class');
            }
        }
    }
}
