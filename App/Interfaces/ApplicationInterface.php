<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ApplicationInterface
{
    public function run(): void;
}