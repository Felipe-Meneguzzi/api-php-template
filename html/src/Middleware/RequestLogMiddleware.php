<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Exception\AppException;
use App\Core\Http\HTTPRequest;
use App\Module\RequestLog\DTO\Input\RequestLogIDTO;
use App\Module\RequestLog\Service\IRequestLogService;

class RequestLogMiddleware {
    public function __construct(protected IRequestLogService $service) {}

    public function handle(HTTPRequest $request, callable $next) {
        $iDTO = new RequestLogIDTO(
            user_id: 1 ?? 1,
            uri: $request->uri ??'Unknown',
            method: $request->method ?? 'Unknown',
            headers: $request->headers ?? ['Unknown'],
            body: $request->body ?? ['Unknown'],
            cookies: $request->cookies ?? ['Unknown'],
            agent: $request->userAgent ?? 'Unknown',
            time: $request->requestTime['data'].' '.$request->requestTime['hora'] ?? 'Unknown',
            ip: $request->requestIP ?? 'Unknown',
        );

        $this->service->Run($iDTO);

        return $next($request);
    }

}