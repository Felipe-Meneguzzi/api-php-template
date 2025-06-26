<?php
declare(strict_types=1);

namespace App\IntegrityModel;

use App\Core\Exception\AppStackException;
use App\Core\IntegrityModelCore;
use App\ValueObject\Email;
use App\ValueObject\Phone;
use Ramsey\Uuid\Uuid;

final readonly class UserIntegrityModel extends IntegrityModelCore {
    private string $uuid;
    private string $name;
    private string $login;
    private string $password;
    private string $email;
    private ?string $phone;

    public function __construct(?string $name, ?string $login, ?string $password, ?Email $email, ?Phone $phone) {
        $this->validate($name, $login, $password, $email, $phone);

        $this->uuid = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email->__toString();
        if (!empty($phone)) {
            $this->phone = $phone->__toString();
        }

        parent::__construct();
    }

    public function validate(?string $name, ?string $login, ?string $password, ?Email $email, ?Phone $phone): void {
        $errors = [];
        if (empty($name)) {
            $errors[] = "field 'name' cannot be empty";
        }
        if (empty($login)) {
            $errors[] = "field 'login' cannot be empty";
        }
        if (empty($password)) {
            $errors[] = "field 'password' cannot be empty";
        }
        if (empty($email)) {
            $errors[] = "field 'email' cannot be empty";
        }

        if (!empty($errors)) {
            throw new AppStackException($errors, 400);
        }
    }

    public function equals(UserIntegrityModel $other): bool {
        return $this->value === $other->value;
    }
}