<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Core\Exception\NotFoundException;
use App\Entity\UserEntity;
use App\IntegrityModel\UserIntegrityModel;
use App\Module\User\DTO\Input\UpdateUserIDTO;
use App\Module\User\Repository\IUserRepository;
use App\ValueObject\Email;
use App\ValueObject\Phone;

interface IUpdateUserService {
	public function run(UpdateUserIDTO $iDTO): array;
}

class UpdateUserService implements IUpdateUserService {
	public function __construct(protected IUserRepository $repository) {}

	public function run(UpdateUserIDTO $iDTO): array {
        $originalEntity = $this->repository->getById($iDTO->uuid);
        if (!$originalEntity) {
            throw new NotFoundException('User');
        }


        $userIntegrity = new UserIntegrityModel(
            name: trim($iDTO->name),
            login: $originalEntity->login,
            password: $originalEntity->password,
            email: Email::fromString(trim($originalEntity->email)),
            phone: Phone::fromString(trim($iDTO->phone)),
            uuid: $iDTO->uuid
        );

        $user = new UserEntity($userIntegrity->__toArray());

		$data = $this->repository->update($user);

        return [
            'statusCode' => 201,
            'data' => $data,
            'message' => 'User updated'
        ];
	}
}