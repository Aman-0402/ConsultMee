<?php
declare(strict_types=1);

namespace ConsultMee\Middleware;

use ConsultMee\Core\Request;
use ConsultMee\Core\Response;

class CsrfMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        if ($request->method() !== 'POST') {
            return;
        }
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals((string)($_SESSION['_csrf_token'] ?? ''), $token)) {
            http_response_code(419);
            exit('CSRF token mismatch.');
        }
    }
}
