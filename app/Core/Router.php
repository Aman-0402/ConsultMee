<?php
declare(strict_types=1);

namespace ConsultMee\Core;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => $path,
            'pattern'    => $this->pathToPattern($path),
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }

    private function pathToPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function resolve(): string
    {
        $method = $this->request->method();
        $path   = $this->request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (!preg_match($route['pattern'], $path, $matches)) continue;

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $this->request->setParams($params);

            foreach ($route['middleware'] as $mwClass) {
                (new $mwClass())->handle($this->request, $this->response);
            }

            [$controllerClass, $action] = $route['handler'];
            $controller = new $controllerClass();
            ob_start();
            $result = $controller->$action();
            $output = ob_get_clean();
            return $output . ($result ?? '');
        }

        return $this->handleNotFound();
    }

    private function handleNotFound(): string
    {
        http_response_code(404);
        if ($this->request->isJson() || $this->request->isAjax()) {
            header('Content-Type: application/json');
            return json_encode(['error' => 'Not found']);
        }
        ob_start();
        View::render('errors/404', [], null);
        return ob_get_clean();
    }
}
