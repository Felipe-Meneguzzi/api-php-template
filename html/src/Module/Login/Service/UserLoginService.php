<?php
declare(strict_types=1);

namespace App\Module\Login\Service;

use App\Core\Exception\AppException;
use App\Module\Login\Repository\IUserLoginRepository;

interface IUserLoginService {
	public function __construct(IUserLoginRepository $repository);
	public function Run(string $login, string $password): array;
}

class UserLoginService implements IUserLoginService {
	private IUserLoginRepository $repository;

	public function __construct(IUserLoginRepository $repository) {
		$this->repository = $repository;
	}

	public function Run(string $login, string $password): array {
		$user = $this->repository->findByLogin($login);

        if (!$user) {
            throw new AppException('User not found', 404);
        }

		return ['data' => [($user)]];
		//return ['data' => [password_verify($password, $user->password)]];
	}
}