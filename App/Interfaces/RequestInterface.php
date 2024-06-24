<?php

declare(strict_types=1);

namespace App\Interfaces;

interface RequestInterface
{
    public function getEndpoint(): ?string;

    public function getParameters(): ?array;

    public function getMethod(): ?string;

    public function getRequestData(): ?string;
}
