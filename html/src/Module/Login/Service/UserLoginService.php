<?php
declare(strict_types=1);

namespace App\Module\Login\Service;

use App\Core\DTOInterface;
use App\Core\Exception\AppException;
use App\Module\Login\Repository\IUserLoginRepository;

interface IUserLoginService {
	public function __construct(IUserLoginRepository $repository);
	public function Run(DTOInterface $iDTO): array;
}

class UserLoginService implements IUserLoginService {
	private IUserLoginRepository $repository;

	public function __construct(IUserLoginRepository $repository) {
		$this->repository = $repository;
	}

	public function Run(DTOInterface $iDTO): array {
		$user = $this->repository->findByLogin($iDTO->login);

        if (!$user) {
            throw new AppException('User not found', 404);
        }

        if (!password_verify($iDTO->password, $user->password)) {
            throw new AppException('Wrong password', 401);
        }

        return [
            'message' => 'Login successful'
        ];
	}
}