<?php
declare(strict_types = 1);

namespace App\Module\User\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HttpRequest;
use App\Module\User\Service\IGetUserByIdService;
use OpenApi\Attributes as OA;

class GetUserByIdController {
    #[OA\Get(
        path: '/auth/user/{id}',
        summary: 'Busca um usuário pelo ID',
        security: [['bearerAuth' => []]],
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID do usuário a ser buscado',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid'),
                example: 'c255f364-50f7-11f0-92f8-4af298741892'
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Operação bem-sucedida'),
            new OA\Response(response: 404, description: 'Usuário não encontrado'),
            new OA\Response(response: 401, description: 'Não autorizado (token inválido ou ausente)')
        ]
    )]
    public function __construct(protected IGetUserByIdService $service) {}

	public function run(HTTPRequest $request): DefaultResponse {
        $id = $request->dynamicParams['id'] ?? '';

		$serviceResponse = $this->service->run($id);

        return DefaultResponse::getDefaultResponse($serviceResponse);
	}

}