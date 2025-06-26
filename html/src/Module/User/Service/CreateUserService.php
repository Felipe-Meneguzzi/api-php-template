<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Entity\UserEntity;
use App\IntegrityModel\UserIntegrityModel;
use App\Module\User\DTO\Input\CreateUserIDTO;
use App\Module\User\Repository\IUserRepository;
use App\ValueObject\Email;
use App\ValueObject\Phone;
use Illuminate\Support\Facades\Password;

interface ICreateUserService {
	public function run(CreateUserIDTO $iDTO): array;
}

class CreateUserService implements ICreateUserService {
	public function __construct(protected IUserRepository $repository) {}

	public function run(CreateUserIDTO $iDTO): array {
        $email = Email::fromString(trim($iDTO->email));
        $phone = Phone::fromString(trim($iDTO->phone));

        $userIntegrity = new UserIntegrityModel(
            name: trim($iDTO->name),
            login:  trim($iDTO->login),
            password: trim($iDTO->password),
            email: $email,
            phone: $phone
        );

        $user = new UserEntity($userIntegrity->__toArray());

		$data = $this->repository->create($user);

        return [
            'statusCode' => 201,
            'data' => $data,
            'message' => 'User created'
        ];
	}
}