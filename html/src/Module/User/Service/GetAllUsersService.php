<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Repository\IUserRepository;
use App\Core\Exception\NotFoundException;

interface IGetAllUsersService {
	public function Run(): array;
}

class GetAllUsersService implements IGetAllUsersService {
	public function __construct(protected IUserRepository $repository) {}

	public function Run(): array {
		$entitiesArray = $this->repository->getAll();

        if (!$entitiesArray) {
            throw new NotFoundException('Users');
        }

        return [
            'data' => $entitiesArray
        ];
	}
}