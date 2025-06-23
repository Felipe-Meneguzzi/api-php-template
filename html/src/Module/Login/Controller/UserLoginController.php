<?php
declare(strict_types = 1);

namespace App\Module\Login\Controller;

use App\Core\DefaultController;
use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\Login\DTO\Input\UserLoginIDTO;
use App\Module\Login\Service\IUserLoginService;

class UserLoginController extends DefaultController{
	private IUserLoginService $service;

	public function __construct(IUserLoginService $service) {
		$this->service = $service;
	}

	public function Run(HTTPRequest $request): void {
		$login = $request->body['login'] ?? null;
		$password = $request->body['password'] ?? null;

        if (empty($login) || empty($password)) {
            throw new AppException("Valor vazio enviado, por favor envie 'login' e 'password'", 400);
        }

        $iDTO = new UserLoginIDTO($login, $password);

		$serviceResponse = $this->service->Run($iDTO);

        $response = $this->getDefaultResponse($serviceResponse);

        $response->sendResponse();
	}

}