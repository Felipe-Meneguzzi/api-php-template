<?php
declare(strict_types=1);

namespace App\Module\User\DTO\Input;

use App\Core\DTOInterface;

readonly class CreateUserIDTO implements DTOInterface {
    public function __construct(
        public ?string $name,
        public ?string $login,
        public ?string $password,
        public ?string $email,
        public ?string $phone
    ) {}
}