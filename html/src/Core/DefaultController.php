<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Http\DefaultResponse;

class DefaultController {
    protected function getDefaultResponse(array $serviceResponse): DefaultResponse {
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