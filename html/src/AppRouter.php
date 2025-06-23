<?php
declare(strict_types = 1);

namespace App;

use App\Core\AppDIContainer;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;
use App\Module\Login\Controller\UserLoginController;

class AppRouter {

	public function __construct() {

	}

	public function Redirect(HTTPRequest $request): DefaultResponse {
		$container = AppDIContainer::build();
		$controller = $container->get(UserLoginController::class);

		return $controller->Run($request);
	}
}