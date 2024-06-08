<?php

declare(strict_types=1);

namespace App\Interfaces;

interface HttpResponseInterface
{
    public static function sendResponse(string $data, int $statusCode): void;
}
