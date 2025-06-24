<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Exception\RequiredParamException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\Service\IGetUserByIdService;

class GetUserByIdController {
	public function __construct(protected IGetUserByIdService $service) {}

	public function Run(HTTPRequest $request): DefaultResponse {
        if(!isset($request->dynamicParams['id'])) {
            throw new RequiredParamException(['id']);
        }

        $id = $request->dynamicParams['id'];

		$serviceResponse = $this->service->Run($id);

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}