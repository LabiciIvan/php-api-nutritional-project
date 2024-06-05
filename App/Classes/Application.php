<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\ApplicationInterface;

class Application implements ApplicationInterface
{
    public function run(): void
    {
        echo 'start application';
    }
}
