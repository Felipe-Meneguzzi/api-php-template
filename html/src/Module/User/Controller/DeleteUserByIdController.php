<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\Service\IDeleteUserByIdService;

class DeleteUserByIdController {
    public function __construct(protected IDeleteUserByIdService $service) {}

	public function run(HTTPRequest $request): DefaultResponse {
        $id = $request->dynamicParams['id'] ?? '';

		$serviceResponse = $this->service->run($id);

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}