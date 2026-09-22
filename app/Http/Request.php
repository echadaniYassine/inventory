<?php

namespace App\Http;

class Request
{
    public function __construct(
        private string $method,
        private string $uri,
        private array $query,
        private array $body,
        private array $headers
    ) {}

    public static function capture(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/',
            $_GET,
            $_POST,
            self::captureHeaders()
        );
    }

    private static function captureHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $headerName = str_replace(
                    ' ',
                    '-',
                    ucwords(
                        strtolower(
                            str_replace('_', ' ', substr($name, 5))
                        )
                    )
                );

                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function query(): array
    {
        return $this->query;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function headers(): array
    {
        return $this->headers;
    }
    public function path(): string
    {
        $path = parse_url($this->uri(), PHP_URL_PATH);

        return $path ?: '/';
    }
}
