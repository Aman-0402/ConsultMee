<?php
declare(strict_types=1);

define('APP_NAME', env('APP_NAME', 'ConsultMee'));
define('APP_URL',  env('APP_URL',  'http://localhost'));

define('UPLOAD_PATH_CONSULTANTS', APP_ROOT . '/public/uploads/consultants/');
define('UPLOAD_PATH_USERS',       APP_ROOT . '/public/uploads/users/');
define('UPLOAD_URL_CONSULTANTS',  '/uploads/consultants/');
define('UPLOAD_URL_USERS',        '/uploads/users/');

define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
