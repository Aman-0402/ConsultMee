<?php
declare(strict_types=1);

use ConsultMee\Controllers\Api\UserApiController;
use ConsultMee\Controllers\Api\ConsultantApiController;
use ConsultMee\Controllers\Api\AppointmentApiController;
use ConsultMee\Controllers\Api\ProjectApiController;
use ConsultMee\Controllers\Api\AuthApiController;
use ConsultMee\Middleware\AuthMiddleware;
use ConsultMee\Middleware\ConsultantMiddleware;

$router = \ConsultMee\Core\Application::getInstance()->router;

// User API (session-protected by middleware; no CSRF — SameSite=Lax covers it)
$router->get('/api/consultants',              [UserApiController::class, 'listByInterests'],    [AuthMiddleware::class]);
$router->get('/api/categories',              [UserApiController::class, 'categories'],          [AuthMiddleware::class]);
$router->get('/api/categories/consultants',  [UserApiController::class, 'categoryConsultants'], [AuthMiddleware::class]);
$router->get('/api/user/profile',            [UserApiController::class, 'getProfile'],          [AuthMiddleware::class]);
$router->post('/api/user/profile',           [UserApiController::class, 'updateProfile'],       [AuthMiddleware::class]);

// Appointment API
$router->post('/api/appointments/book',      [AppointmentApiController::class, 'book'],         [AuthMiddleware::class]);
$router->get('/api/user/appointments',       [AppointmentApiController::class, 'userAppointments'], [AuthMiddleware::class]);
$router->get('/api/user/appointments/history', [AppointmentApiController::class, 'userHistory'], [AuthMiddleware::class]);
$router->post('/api/appointments/status',    [AppointmentApiController::class, 'updateStatus'], [AuthMiddleware::class]);
$router->post('/api/ratings',                [AppointmentApiController::class, 'rate'],         [AuthMiddleware::class]);

// Project API (user-facing)
$router->get('/api/projects',               [ProjectApiController::class, 'list'],         [AuthMiddleware::class]);
$router->post('/api/projects',              [ProjectApiController::class, 'create'],        [AuthMiddleware::class]);
$router->get('/api/projects/applications',  [ProjectApiController::class, 'applications'], [AuthMiddleware::class]);

// Project apply (consultant)
$router->post('/api/projects/apply',        [ProjectApiController::class, 'apply'],         [ConsultantMiddleware::class]);

// Consultant API
$router->get('/api/consultant/profile',                   [ConsultantApiController::class, 'getProfile'],           [ConsultantMiddleware::class]);
$router->post('/api/consultant/profile',                  [ConsultantApiController::class, 'updateProfile'],        [ConsultantMiddleware::class]);
$router->get('/api/consultant/appointments/requests',     [ConsultantApiController::class, 'appointmentRequests'],  [ConsultantMiddleware::class]);
$router->get('/api/consultant/appointments/confirmed',    [ConsultantApiController::class, 'confirmedAppointments'],[ConsultantMiddleware::class]);
$router->get('/api/consultant/appointments/history',      [ConsultantApiController::class, 'appointmentHistory'],   [ConsultantMiddleware::class]);
$router->post('/api/consultant/appointments',             [ConsultantApiController::class, 'updateAppointment'],    [ConsultantMiddleware::class]);
$router->get('/api/consultant/projects',                  [ConsultantApiController::class, 'browseProjects'],       [ConsultantMiddleware::class]);

// Auth OTP (no auth middleware — public endpoints)
$router->post('/api/auth/send-otp',              [AuthApiController::class, 'sendOtp']);
$router->post('/api/auth/verify-otp',            [AuthApiController::class, 'verifyOtp']);
$router->post('/api/auth/consultant/send-otp',   [AuthApiController::class, 'consultantSendOtp']);
$router->post('/api/auth/consultant/verify-otp', [AuthApiController::class, 'consultantVerifyOtp']);
