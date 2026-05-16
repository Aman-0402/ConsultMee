<?php
declare(strict_types=1);

if (!defined('APP_ROOT')) define('APP_ROOT', __DIR__);
if (!defined('PUBLIC_ROOT')) define('PUBLIC_ROOT', APP_ROOT . '/public');

// ── Load .env ──────────────────────────────────────────────────────────────
$envFile = APP_ROOT . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v, " \t\"'");
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
}

// ── PSR-4 Autoloader ───────────────────────────────────────────────────────
spl_autoload_register(static function (string $class): void {
    $prefix = 'ConsultMee\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;

    $relative = substr($class, strlen($prefix));

    $pathMap = [
        'Controllers\\Api\\' => APP_ROOT . '/app/Controllers/Api/',
        'Controllers\\'      => APP_ROOT . '/app/Controllers/',
        'Core\\'             => APP_ROOT . '/app/Core/',
        'Models\\'           => APP_ROOT . '/app/Models/',
        'Middleware\\'       => APP_ROOT . '/app/Middleware/',
        'Services\\'         => APP_ROOT . '/app/Services/',
        'Helpers\\'          => APP_ROOT . '/app/Helpers/',
    ];

    foreach ($pathMap as $ns => $dir) {
        if (strncmp($relative, $ns, strlen($ns)) === 0) {
            $file = $dir . str_replace('\\', '/', substr($relative, strlen($ns))) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
            return;
        }
    }
});

// ── Helpers ────────────────────────────────────────────────────────────────
require_once APP_ROOT . '/app/Helpers/helpers.php';

// ── App Config ─────────────────────────────────────────────────────────────
require_once APP_ROOT . '/config/app.php';

// ── Storage directories ────────────────────────────────────────────────────
foreach (['logs', 'sessions', 'cache', 'temp'] as $dir) {
    $path = APP_ROOT . '/storage/' . $dir;
    if (!is_dir($path)) mkdir($path, 0755, true);
}

// ── Session ────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_save_path(APP_ROOT . '/storage/sessions');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ── Error handling ─────────────────────────────────────────────────────────
if (env('APP_ENV', 'production') === 'local') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
    ini_set('log_errors', '1');
    ini_set('error_log', APP_ROOT . '/storage/logs/php_errors.log');
}
