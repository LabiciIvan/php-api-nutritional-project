<?php

declare(strict_types=1);

namespace App\Utilities;

use App\Interfaces\HttpResponseInterface;

class Response implements HttpResponseInterface
{
    public static function sendResponse(string $data, int $statusCode): void
    {
        http_response_code($statusCode);

        echo $data;

        exit;
    }
}
