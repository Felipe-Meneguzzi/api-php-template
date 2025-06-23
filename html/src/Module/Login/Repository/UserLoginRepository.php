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

	public function __construct(IDBConnection $dbClass) {
		$this->db = $dbClass->getConnection();
	}

	public function findByLogin(string $login) {
        $user = UserEntity::where('login', $login)->first();

        if (!$user) {
            return null;
        }

        return $user;
	}
}