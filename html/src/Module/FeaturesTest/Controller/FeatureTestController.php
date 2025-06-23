<?php
declare(strict_types=1);

namespace App\Module\FeaturesTest\Controller;

use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;

class FeatureTestController {
    public function Run(HTTPRequest $request, array $params): DefaultResponse{
        return new DefaultResponse(
            statusCode: 201,
            data: ['controllerParams' =>  $params,
                   'request' => $request],
            metadata: [],
            message: '',
            errors: [],
            headers: []
        );
    }
}