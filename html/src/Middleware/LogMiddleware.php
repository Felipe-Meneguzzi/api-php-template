<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Http\HTTPRequest;
use App\Module\RequestLog\Service\IRequestLogService;

class LogMiddleware {
    public function __construct(protected IRequestLogService $service) {

    }

    public function handle(HTTPRequest $request, callable $next) {

        return $next($request);
    }

}