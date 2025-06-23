<?php
declare(strict_types=1);

namespace App\Module\FeaturesTest\Middleware;

use App\Core\Http\HTTPRequest;

class FeatureTestMiddleware {
    public function handle(HTTPRequest $request, callable $next) {
        $request->middlewareParams['parametro_string'] = 'esse parametro veio do middleware';
        $request->middlewareParams['parametro_int'] = 2;
        $request->middlewareParams['parametro_bool'] = false;

        return $next($request);
    }

}