<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\Service\IGetAllUsersService;

class GetAllUsersController {
	public function __construct(protected IGetAllUsersService $service) {}

	public function Run(HTTPRequest $request): DefaultResponse {
		$serviceResponse = $this->service->Run();

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}