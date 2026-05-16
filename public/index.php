<?php
declare(strict_types=1);

define('APP_ROOT', realpath(__DIR__ . '/..'));

require APP_ROOT . '/bootstrap.php';

use ConsultMee\Core\Application;

(new Application())->run();
