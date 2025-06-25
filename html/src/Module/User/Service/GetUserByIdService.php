<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Core\Exception\NotFoundException;
use App\Module\User\Repository\IUserRepository;

interface IGetUserByIdService {
	public function Run(string $id): array;
}

class GetUserByIdService implements IGetUserByIdService {
	public function __construct(protected IUserRepository $repository) {}

	public function Run(string $id): array {
        $entity = $this->repository->getById($id);

        if (!$entity) {
            throw new NotFoundException('User');
        }

        return [
            'data' => $entity
        ];
	}
}