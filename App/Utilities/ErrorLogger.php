<?php

declare(strict_types=1);

namespace App\Utilities;

class ErrorLogger
{
    public static function logError(string $errorMessage, string $path): void
    {
        $timestamp = date('Y-m-d H:i:s');

        $formattedMessage = "[$timestamp] - {$errorMessage}" . PHP_EOL;

        file_put_contents($path, $formattedMessage, FILE_APPEND);
    }
}
