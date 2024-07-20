<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Classes\BaseMiddleware;

/**
 * Base class for Controllers.
 *
 * Extend this class to use middlewares.
 *
 * Use the middleware() method in the child class constructor
 * to register middlewares from BaseMiddleware::class.
 */
class BaseController
{
    /**
     *  Middlewares applied to a controller
     */
    protected    array    $middlewares = [];

    /**
     * Applies middlewares to a controller.
     *
     * Collects and stacks middlewares for a controller, then
     * calls callBaseMiddlewareClass() to execute them.
     *
     * @param    array    $middlewareName    Middlewares which apply to a controller and
     *                                       are registered in BaseMiddleware::class
     * @return   void
     */
    public function middleware(array $middlewareName): void
    {
        foreach ($middlewareName as $name) {
            if (!in_array($name, $this->middlewares)) {
                $this->middlewares[] = $name;
            }
        }

        $this->callBaseMiddlewareClass();
    }

    /**
     * Instantiate BaseMiddleware.
     * 
     * Creates a new instance of BaseMiddleware::class
     * by passing the collected $middlewares array.
     * 
     * @return   void
     */
    private function callBaseMiddlewareClass(): void
    {
        new BaseMiddleware($this->middlewares);
    }
}
