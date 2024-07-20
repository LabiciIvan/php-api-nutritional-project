<?php

declare(strict_types=1);

namespace App\Classes;

use Error;
use App\Utilities\ErrorLogger;

/**
 * Base class for middlewares.
 * 
 * When this class is instantiated it will iterate the
 * provided $middlewares array and call each middleware
 * that might exist in the $registeredMiddlewares array.
 * 
 * The middlewares which are not registerd will be logged
 * in the errors.txt file as not registered middlewares
 */
class BaseMiddleware
{
    /**
     * Middlewares available in the application.
     */
    private    array    $registeredMiddlewares = [];

    public function __construct(array $middlewares)
    {
        foreach ($middlewares as $middlewareName) {
            if (isset($this->registeredMiddlewares[$middlewareName])) {
                $class = null;
                $method = null;

                // If array then check if class and method exists
                if (is_array($this->registeredMiddlewares[$middlewareName]) && count($this->registeredMiddlewares[$middlewareName]) === 2) {
                    $class = $this->registeredMiddlewares[$middlewareName][0];
                    $method = $this->registeredMiddlewares[$middlewareName][1];
                } else if (!is_array($this->registeredMiddlewares[$middlewareName])) {
                    $class = $this->registeredMiddlewares[$middlewareName];
                }

                if (isset($class, $method) && class_exists($class)) {
                    $classInstance = new $class();

                    try {
                        $classInstance->$method();
                    } catch (Error $e) {
                        ErrorLogger::logError($e->getMessage(). ' in ' .__CLASS__. ' line: ' .__LINE__, __DIR__ . '/../../errors.txt');
                        continue;
                    }
                } else if (isset($class) && class_exists($class)) {
                    $classInstance = new $class();
                } else {
                    ErrorLogger::logError('Middleware class or method does not exist for registered middleware: ' . $middlewareName, __DIR__ . '/../../errors.txt');
                }

            } else {
                ErrorLogger::logError('Middleware used might not be set: ' . $middlewareName, __DIR__ . '/../../errors.txt');
            }
        }

    }
}
