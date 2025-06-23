<?php
declare(strict_types=1);

namespace App;

use App\Core\Exception\AppException;
use App\Core\Http\HTTPRequest;

class Router {
    protected array $routes = [];
    protected array $groupStack = [];
    protected HTTPRequest $request;

    public function __construct(HTTPRequest $request) {
        $this->request = $request;
    }

    public function group(array $attributes, callable $callback): void {
        $this->groupStack[] = $attributes;

        $callback($this);

        array_pop($this->groupStack);
    }

    public function register(string $uri, string $method, $handler, array $middleware = []): void {
        $prefix = '';
        $groupMiddleware = [];

        if (!empty($this->groupStack)) {
            foreach ($this->groupStack as $group) {
                $prefix .= $group['prefix'] ?? '';
                $groupMiddleware = array_merge($groupMiddleware, $group['middleware'] ?? []);
            }
        }

        $finalMiddleware = array_unique(array_merge($groupMiddleware, $middleware));

        $this->routes[strtoupper($method)][$prefix . $uri] = [
            'handler' => $handler,
            'middleware' => $finalMiddleware
        ];
    }

    public function get(string $uri, $handler, array $attributes = []): void {
        $this->register($uri, 'GET', $handler, $attributes['middleware'] ?? []);
    }

    public function post(string $uri, $handler, array $attributes = []): void {
        $this->register($uri, 'POST', $handler, $attributes['middleware'] ?? []);
    }

    public function put(string $uri, $handler, array $attributes = []): void {
        $this->register($uri, 'PUT', $handler, $attributes['middleware'] ?? []);
    }

    public function patch(string $uri, $handler, array $attributes = []): void {
        $this->register($uri, 'PATCH', $handler, $attributes['middleware'] ?? []);
    }

    public function delete(string $uri, $handler, array $attributes = []): void {
        $this->register($uri, 'DELETE', $handler, $attributes['middleware'] ?? []);
    }

    public function dispatch(): void {
        $method = strtoupper($this->request->method);
        $uri = parse_url($this->request->uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $handler = $route['handler'];
            $middleware = $route['middleware'];

            $allMiddlewarePassed = $this->executeMiddleware($middleware);

            if ($allMiddlewarePassed) {
                $this->callAction($handler, 'Run');
            }

        } else {
            throw new AppException("Route not found", 404);
        }
    }

    protected function executeMiddleware(array $middleware): bool {
        foreach ($middleware as $mw) {
            if (class_exists($mw)) {
                require_once "middleware/{$mw}.php";
            } else {
                throw new AppException("Middleware file '{$mw}.php' not found", 500);
            }

            if (class_exists($mw)) {
                $instance = new $mw();
                if (!$instance->handle()) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function callAction($controller, string $action): void {
        if (method_exists($controller, $action)) {
            $controller->$action($this->request);
        } else {
            throw new AppException("Controller or method not found: {$controller}@{$action}", 500);
        }
    }
}