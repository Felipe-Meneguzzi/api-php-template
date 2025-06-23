<?php
declare(strict_types=1);

namespace App\Module\RequestLog\Service;

use App\Core\Http\HTTPRequest;

interface IRequestLogService {
	public function Run(HTTPRequest $request): array;
}

class RequestLogService implements IRequestLogService {
	public function __construct() {

	}

	public function Run(HTTPRequest $request): array {
		return [];
	}
}