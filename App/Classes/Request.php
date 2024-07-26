<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    private ?string $method = null;

    private ?string $endpoint = null;

    private ?array $parameters = null;

    private ?string $bearderToken = null;

    private ?string $data = null;

    public function __construct(string $requestMethod = null, string $requestURL = null, string $authorizationHeader = null)
    {
        if (!$requestMethod || !$requestURL) {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestURL = $_SERVER['REQUEST_URI'];
        }

        $this->processServerData($requestMethod, $requestURL, $authorizationHeader);
    }

    private function processServerData(string $rawRequestMethod, string $rawRequestURL, string $authorizationHeader = null): void
    {
        // process the request method.
        $this->method = $rawRequestMethod;

        // Process the URL endpoint and parameters.
        $parsedURLParameters = parse_url($rawRequestURL);

        $this->endpoint = $parsedURLParameters['path'];

        if (isset($parsedURLParameters['query'])) {
            parse_str($parsedURLParameters['query'], $this->parameters);
        }

        // Process the request body data.
        $requestInput = file_get_contents('php://input');

        $this->data = !empty($requestInput) ? $requestInput : null;

        // Process the Authorization Header if available
        if (isset($authorizationHeader)) {
            $authorizationHeader = trim($authorizationHeader);

            // Match the Bearer Token from Headers
            if (preg_match('/^Bearer\s(\S+)$/', $authorizationHeader, $matches)) {
                $this->bearderToken = $matches[1];
            }
        }
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getRequestData(): ?string
    {
        return $this->data;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function getBearerToken(): ?string
    {
        return $this->bearderToken;
    }
}
