<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Http\HTTPRequest;
use App\Module\Login\Service\IAuthenticateService;

class AuthenticateMiddleware {
    public function __construct(protected IAuthenticateService $service) {}

    public function handle(HTTPRequest $request, callable $next) {
        $this->service->Run();

        return $next($request);
    }

}