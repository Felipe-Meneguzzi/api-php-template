<?php
declare(strict_types=1);

namespace App\Module\RequestLog\DTO\Input;

use App\Core\DTOInterface;

readonly class RequestLogIDTO implements DTOInterface {
    public function __construct(
        public ?int $user_id,
        public ?string $uri,
        public ?string $method,
        public ?array $headers,
        public ?array $body,
        public ?array $cookies,
        public ?string $agent,
        public ?string $time,
        public ?string $ip,
    ) {}
}