<?php
declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Core\DB\IDBConnection;
use App\Entity\UserEntity;
use Illuminate\Database\Connection;

interface IUserRepository {
	public function getAll(): array;
    public function getById(string $id): ?UserEntity;
    public function create(UserEntity $userEntity): UserEntity;
}

class UserRepository implements IUserRepository {
	private Connection $db;
    private string $table;
    private string $entityClass;

	public function __construct(protected IDBConnection $dbClass) {
		$this->db = $dbClass->getConnection();
        $this->table = 'users';
        $this->entityClass = UserEntity::class;
	}

    public function getAll(): array {
        $returnArray = [];
        $dataArray = $this->db->table($this->table)
            ->get();

        foreach ($dataArray as $data){
            $entity = new $this->entityClass;

            $returnArray[] = $entity->newInstance((array) $data, true);
        }

        return $returnArray;
    }

    public function getById(string $id): ?UserEntity {
        $data = $this->db->table($this->table)
            ->where('uuid', $id)
            ->first();

        if (!$data) {
            return null;
        }

        $entity = new $this->entityClass;

        return $entity->newInstance((array) $data, true);
    }

    public function create(UserEntity $entity): UserEntity {
        $entity->setConnection($this->db->getName());

        $entity->save();

        return $entity;
    }
}