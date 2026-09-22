<?php

namespace App\Http;

class Response
{
    public function __construct(
        private string $body = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {}

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }
}