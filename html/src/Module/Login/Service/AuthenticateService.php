<?php
declare(strict_types=1);

namespace App\Module\Login\Service;

use App\ValueObject\JWTToken;

interface IAuthenticateService {
	public function Run(string $token): void;
}

class AuthenticateService implements IAuthenticateService {
	public function __construct() {}

	public function Run(string $token): void {
        JWTToken::decode($token);
	}
}