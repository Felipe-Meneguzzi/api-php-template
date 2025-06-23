<?php
declare(strict_types=1);

namespace App\Module\Login\DTO\Input;

use App\Core\DTOInterface;

readonly class UserLoginIDTO implements DTOInterface {
    public function __construct(
        public string $login,
        public string $password
    ) {}
}