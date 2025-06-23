<?php
declare(strict_types=1);

namespace App\Module\Login\Repository;

use App\Core\DB\IDBConnection;
use App\Entity\UserEntity;
use Illuminate\Database\Connection;

interface IUserLoginRepository {
	public function findByLogin(string $login);
}

class UserLoginRepository implements IUserLoginRepository {
	private Connection $db;
    private string $table;

	public function __construct(protected IDBConnection $dbClass, protected UserEntity $entity) {
		$this->db = $dbClass->getConnection();
        $this->table = $this->entity->getTable();
	}

	public function findByLogin(string $login): ?UserEntity {
        $user_data = $this->db->table($this->table)
            ->where('login', $login)
            ->first();

        if (!$user_data) {
            return null;
        }

        return $this->entity->newInstance((array) $user_data, true);
	}
}