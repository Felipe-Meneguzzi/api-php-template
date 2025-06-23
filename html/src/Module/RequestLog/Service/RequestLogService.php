<?php
declare(strict_types=1);

namespace App\Module\RequestLog\Service;

interface IRequestLogService {
	public function Run(): array;
}

class RequestLogService implements IRequestLogService {
	public function __construct() {

	}

	public function Run(): array {
		return [];
	}
}