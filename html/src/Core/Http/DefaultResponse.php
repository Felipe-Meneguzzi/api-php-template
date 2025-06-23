<?php

namespace App\Core\Http;

use App\Core\ObjectCore;

class DefaultResponse extends ObjectCore {
    public array $headers;
    public array $body;

    public function __construct(
        int $statusCode,
        array $data = [],
        array $metadata = [],
        string $message = '',
        array $errors = [],
        array $headers = ['Content-Type' => 'application/json; charset=UTF-8'],
    ) {
        $this->body = [
            'success' => $statusCode >= 200 && $statusCode < 300,
            'statusCode' => $statusCode,
            'data' => $data,
            'metadata' => $metadata,
            'message' => $message,
            'errors' => $errors,
        ];
        $this->headers = $headers;
    }

    public function sendResponse (): void {
        http_response_code($this->body['statusCode']);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo json_encode($this->body);
    }
}