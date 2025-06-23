<?php
declare(strict_types = 1);

namespace App\Module\Login\Controller;

use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\Login\Service\IUserLoginService;

class UserLoginController {
	private IUserLoginService $service;

	public function __construct(IUserLoginService $service) {
		$this->service = $service;
	}

	public function Run(HTTPRequest $request): DefaultResponse {
		$login = $request->body['login'] ?? null;
		$password = $request->body['password'] ?? null;

		if (empty($login) || empty($password)) {
			throw new AppException("Valor vazio enviado, por favor envie 'login' e 'password'", 400);
		}

		$serviceResponse = $this->service->Run($login, $password);

		$response = new DefaultResponse(
			statusCode: $serviceResponse['statusCode'] ?? 200,
			data: $serviceResponse['data'] ?? [],
			metadata:  $serviceResponse['metadata'] ?? [],
			message: $serviceResponse['message'] ?? '',
			errors: $serviceResponse['errors'] ?? [],
		);

		if (isset($serviceResponse['headers'])) {
			$response->headers = $serviceResponse['headers'];
		}

		return $response;
	}

}