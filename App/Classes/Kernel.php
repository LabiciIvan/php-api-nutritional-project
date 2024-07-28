<?php

declare(strict_types=1);

namespace App\Classes;

use App\Classes\Request;
use App\Classes\Router;

class Kernel
{
    public Router $router;

    public Request $request;

    public function __construct(Request $request, Router $router)
    {
        $this->router = $router;

        $this->request = $request;
    }
}
