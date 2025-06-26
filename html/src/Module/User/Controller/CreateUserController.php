<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\DTO\Input\CreateUserIDTO;
use App\Module\User\Service\ICreateUserService;

class CreateUserController {
    public function __construct(protected ICreateUserService $service) {}

	public function run(HTTPRequest $request): DefaultResponse {
        $iDTO = new CreateUserIDTO(
            name: $request->body['name'] ?? null,
            login:  $request->body['login'] ?? null,
            password:  $request->body['password'] ?? null,
            email:  $request->body['email'] ?? null,
            phone:  $request->body['phone'] ?? null
        );

		$serviceResponse = $this->service->run($iDTO);

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}