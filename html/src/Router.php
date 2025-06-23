<?php
declare(strict_types=1);

namespace App;

use App\Core\Exception\AppException;
use App\Core\Http\DefaultResponse;
use App\Core\Http\HTTPRequest;
use DI\Container;

class Router {
    protected array $routes = [];
    protected array $groupStack = [];

    public function __construct(
        protected readonly HTTPRequest $request,
        protected readonly Container $container
    ) {

    }

    public function group(array $attributes, callable $callback): void {
        $this->groupStack[] = $attributes;

        $callback($this);

        array_pop($this->groupStack);
    }

    public function register(string $uri, string $method, $handler, array $middleware = [], array $controllerParams = []): void {
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
            'controllerParams' => $controllerParams,
            'middleware' => $finalMiddleware
        ];
    }

    public function get(string $uri, array|callable $handler, array $attributes = [], array $controllerParams = []): void {
        $this->register($uri, 'GET', $handler, $attributes['middleware'] ?? [], $controllerParams);
    }

    public function post(string $uri, array|callable $handler, array $attributes = [], array $controllerParams = []): void {
        $this->register($uri, 'POST', $handler, $attributes['middleware'] ?? [], $controllerParams);
    }

    public function put(string $uri, array|callable $handler, array $attributes = [], array $controllerParams = []): void {
        $this->register($uri, 'PUT', $handler, $attributes['middleware'] ?? [], $controllerParams);
    }

    public function patch(string $uri, array|callable $handler, array $attributes = [], array $controllerParams = []): void {
        $this->register($uri, 'PATCH', $handler, $attributes['middleware'] ?? [], $controllerParams);
    }

    public function delete(string $uri, array|callable $handler, array $attributes = [], array $controllerParams = []): void {
        $this->register($uri, 'DELETE', $handler, $attributes['middleware'] ?? [], $controllerParams);
    }

    public function dispatch(): void {
        $method = strtoupper($this->request->method);
        $uri = parse_url($this->request->uri, PHP_URL_PATH);

        foreach ($this->routes[$method] ?? [] as $routeUri => $route) {
            // Encontra todas as chaves na URI da rota (ex: {id}, {slug})
            preg_match_all('/\{([a-zA-Z0-9_-]+)\}/', $routeUri, $keys);
            $paramKeys = $keys[1]; // Array com os nomes das chaves: ['id', 'slug']

            // Converte a URI da rota em um padrão regex
            // Ex: /users/{id} -> #^/users/([a-zA-Z0-9_-]+)$#
            $pattern = preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $routeUri);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove o primeiro elemento que é a string completa da URL

                // Combina as chaves extraídas com os valores correspondentes
                // Se houver mais valores do que chaves (ou vice-versa), array_combine lidará com isso
                $this->request->dynamicParams = array_combine($paramKeys, $matches);

                $response = $this->executePipeline($route['handler'], $route['middleware'], $route['controllerParams']);

                $response->sendResponse();

                return;
            }
        }

        throw new AppException("Route not found", 404);
    }

    protected function executePipeline(array|callable $handler, array $middlewares, array $controllerParams): DefaultResponse {
        // O "núcleo" da cebola/pipeline: a ação final que chama o controller.
        $coreAction = function (HTTPRequest $request) use ($handler, $controllerParams) {
            return $this->callAction($handler, $controllerParams);
        };

        // Invertemos o array de middlewares para construir a cadeia de fora para dentro.
        $reversedMiddleware = array_reverse($middlewares);

        // Usamos array_reduce para envolver cada camada da cebola na anterior.
        $pipeline = array_reduce(
            $reversedMiddleware,
            function ($next, $middlewareClass) {
                // Cria uma nova função que chama o handle do middleware atual,
                // passando a próxima camada ($next) como seu callable.
                return function (HTTPRequest $request) use ($middlewareClass, $next) {
                    $middlewareInstance = $this->container->get($middlewareClass);
                    return $middlewareInstance->handle($request, $next);
                };
            },
            $coreAction // O valor inicial é a chamada do nosso controller.
        );

        // Executa a cadeia de middlewares completa, começando pela camada mais externa.
        return call_user_func($pipeline, $this->request);
    }

    protected function callAction($handler, array $params = []): DefaultResponse {
        // Verifica se o handler é o array [classe, método]
        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;

            if (class_exists($controllerClass)) {
                $controllerInstance = $this->container->get($controllerClass);

                if (method_exists($controllerInstance, $method)) {
                    return $controllerInstance->$method($this->request, ...$params);
                }
            }
        }

        // Se o handler for uma Closure/função anônima
        if (is_callable($handler)) {
            return $handler($this->request, ...$params);
        }

        throw new AppException("Invalid handler for the route.", 500);
    }
}