<?php
declare(strict_types=1);

namespace ConsultMee\Middleware;

use ConsultMee\Core\Request;
use ConsultMee\Core\Response;

class ConsultantMiddleware
{
    public function handle(Request $request, Response $response): void
    {
        if (empty($_SESSION['consultant'])) {
            if (str_starts_with($request->path(), '/api/')) {
                $response->unauthorized();
            }
            redirect('/consultant/login?msg=' . urlencode('Please log in to continue.') . '&type=error');
        }
    }
}
