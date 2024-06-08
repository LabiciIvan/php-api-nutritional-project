<?php

declare(strict_types=1);

namespace App\Utilities;

use App\Interfaces\JsonInterface;
use JsonException;

class Json implements JsonInterface
{
    public static function toJson(array $data): ?string
    {
        try {
            $jsonEncoded = json_encode($data, JSON_THROW_ON_ERROR, 512);
        } catch (JsonException $e) {
            return null;
        }

        return $jsonEncoded;
    }

    public static function fromJson(string $data): ?array
    {
        try {
            $jsonDecoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return null;
        }

        return $jsonDecoded;
    }
}
