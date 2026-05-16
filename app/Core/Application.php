<?php
declare(strict_types=1);

namespace ConsultMee\Core;

class Application
{
    private static ?self $instance = null;
    public Router  $router;
    public Request $request;
    public Response $response;

    public function __construct()
    {
        self::$instance = $this;
        $this->request  = new Request();
        $this->response = new Response();
        $this->router   = new Router($this->request, $this->response);

        require_once APP_ROOT . '/routes/web.php';
        require_once APP_ROOT . '/routes/api.php';
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function run(): void
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            if (env('APP_ENV', 'production') === 'local') {
                echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            } else {
                http_response_code(500);
                if ($this->request->isJson() || $this->request->isAjax()) {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Server error']);
                } else {
                    View::render('errors/500', [], null);
                }
            }
        }
    }
}
