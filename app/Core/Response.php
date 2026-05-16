<?php
declare(strict_types=1);

namespace ConsultMee\Core;

class Response
{
    public function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function success(mixed $data = null, string $message = 'OK', int $code = 200): void
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        $this->json($payload, $code);
    }

    public function error(string $message, int $code = 400): void
    {
        $this->json(['success' => false, 'error' => $message], $code);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [], null);
        exit;
    }

    public function forbidden(): void
    {
        $this->json(['error' => 'Forbidden'], 403);
    }

    public function unauthorized(): void
    {
        $this->json(['error' => 'Unauthorized'], 401);
    }
}
