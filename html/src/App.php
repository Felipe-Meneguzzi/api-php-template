<?php
declare(strict_types = 1);

namespace App;

use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;

class App {
	public AppRouter $appRouter;

	public function __construct(AppRouter $appRouter) {
		$this->appRouter = $appRouter;
	}

    public function HandleRequest(HTTPRequest $request): void {
		try {
			// Se o path vier vazio, redireciona para rota de verificação de estado da API
			if (!isset($_GET['path'])) {
				$response = new DefaultResponse(
					statusCode: 200,
					message: 'API ON'
				);

				self::sendResponse($response);
			}

			$response = $this->appRouter->Redirect($request);

			self::sendResponse($response);
		} catch (AppException $appException) {
			$response = new DefaultResponse(
				statusCode: $appException->getCode(),
				message: $appException->getMessage()
			);

			self::sendResponse($response);
		} catch (\Throwable $unhandledException) {
            $response = new DefaultResponse(
                statusCode: 500,
                data: ['ERROR' => $unhandledException->getMessage()],
                message: 'Erro não tratado na API, contate o BackEnd'
            );

			self::sendResponse($response);
        }
    }

	private function sendResponse ($response): void {
		http_response_code($response->body['statusCode']);

		foreach ($response->headers as $name => $value) {
			header("$name: $value");
		}

		echo json_encode($response->body);

		exit;
	}
}


