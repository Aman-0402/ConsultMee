<?php
declare(strict_types=1);

namespace ConsultMee\Middleware;

use ConsultMee\Core\Request;
use ConsultMee\Core\Response;

class AuthMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        if (empty($_SESSION['user'])) {
            if (str_starts_with($request->path(), '/api/')) {
                $response->unauthorized();
            }
            redirect('/login?msg=' . urlencode('Please log in to continue.') . '&type=error');
        }
    }
}
