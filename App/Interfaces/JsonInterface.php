<?php

declare(strict_types=1);

namespace App\Interfaces;

interface JsonInterface
{
    public static function toJson(array $data): ?string;

    public static function fromJson(string $data): ?array;
}
