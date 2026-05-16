<?php
declare(strict_types=1);

use ConsultMee\Controllers\HomeController;
use ConsultMee\Controllers\AuthController;
use ConsultMee\Controllers\DashboardController;
use ConsultMee\Controllers\ConsultantController;
use ConsultMee\Middleware\AuthMiddleware;
use ConsultMee\Middleware\ConsultantMiddleware;

$router = \ConsultMee\Core\Application::getInstance()->router;

// Public pages
$router->get('/',         [HomeController::class, 'home']);
$router->get('/about',    [HomeController::class, 'about']);
$router->get('/services', [HomeController::class, 'services']);
$router->get('/contact',  [HomeController::class, 'contact']);
$router->post('/contact', [HomeController::class, 'sendContact']);

// User auth
$router->get('/login',            [AuthController::class, 'loginForm']);
$router->post('/login',           [AuthController::class, 'login']);
$router->get('/signup',           [AuthController::class, 'signupForm']);
$router->post('/signup',          [AuthController::class, 'signup']);
$router->get('/forgot-password',  [AuthController::class, 'forgotPasswordForm']);
$router->post('/logout',          [AuthController::class, 'logout']);

// Consultant auth
$router->get('/consultant/login',           [AuthController::class, 'consultantLoginForm']);
$router->post('/consultant/login',          [AuthController::class, 'consultantLogin']);
$router->get('/consultant/signup',          [AuthController::class, 'consultantSignupForm']);
$router->post('/consultant/signup',         [AuthController::class, 'consultantSignup']);
$router->get('/consultant/forgot-password', [AuthController::class, 'consultantForgotPasswordForm']);
$router->post('/consultant/logout',         [AuthController::class, 'consultantLogout']);

// Protected — user
$router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

// Protected — consultant
$router->get('/consultant/dashboard',           [ConsultantController::class, 'dashboard'],      [ConsultantMiddleware::class]);
$router->get('/consultant/profile/{username}',  [ConsultantController::class, 'publicProfile'],  [AuthMiddleware::class]);
