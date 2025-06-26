<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\DTO\Input\UpdateUserIDTO;
use App\Module\User\Service\IUpdateUserService;

class UpdateUserController {
    public function __construct(protected IUpdateUserService $service) {}

	public function run(HTTPRequest $request): DefaultResponse {
        $iDTO = new UpdateUserIDTO(
            uuid: $request->body['uuid'] ?? null,
            name: $request->body['name'] ?? null,
            email:  $request->body['email'] ?? null,
            phone:  $request->body['phone'] ?? null
        );

		$serviceResponse = $this->service->run($iDTO);

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}