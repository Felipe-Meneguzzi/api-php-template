<?php
declare(strict_types=1);

namespace App\Module\Login\Service;

interface IAuthenticateService {
	public function Run(): void;
}

class AuthenticateService implements IAuthenticateService {
	public function __construct() {}

	public function Run(): void {

	}
}