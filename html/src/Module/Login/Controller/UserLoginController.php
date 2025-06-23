<?php
declare(strict_types = 1);

namespace App\Module\Login\Controller;

use App\Core\DefaultController;
use App\Core\Exception\RequiredParamException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\Login\DTO\Input\UserLoginIDTO;
use App\Module\Login\Service\IUserLoginService;

class UserLoginController extends DefaultController{
	private IUserLoginService $service;

	public function __construct(IUserLoginService $service) {
		$this->service = $service;
	}

	public function Run(HTTPRequest $request): DefaultResponse {
		$login = $request->body['login'] ?? null;
		$password = $request->body['password'] ?? null;

        if (empty($login) || empty($password)) {
            throw new RequiredParamException();
        }

        $iDTO = new UserLoginIDTO($login, $password);

		$serviceResponse = $this->service->Run($iDTO);

        return $this->getDefaultResponse($serviceResponse);
	}

}