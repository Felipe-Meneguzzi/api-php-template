<?php
declare(strict_types=1);

namespace App\Module\User\DTO\Input;

use App\Core\DTOInterface;

readonly class UpdateUserIDTO implements DTOInterface {
    public function __construct(
        public ?string $uuid,
        public ?string $name,
        public ?string $email,
        public ?string $phone
    ) {}
}