<?php
declare(strict_types=1);

namespace App\Module\Login\DTO\Input;

use App\Core\DTOInterface;

readonly class UserLoginIDTO implements DTOInterface {
    public string $login;
    public string $password;

    public function __construct(string $login, string $password) {
        $this->login = $login;
        $this->password = $password;
    }
}