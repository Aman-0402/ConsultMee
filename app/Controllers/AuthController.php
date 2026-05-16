<?php
declare(strict_types=1);

namespace ConsultMee\Controllers;

use ConsultMee\Core\Controller;
use ConsultMee\Models\User;
use ConsultMee\Models\Consultant;

class AuthController extends Controller
{
    // ── User Auth ───────────────────────────────────────────────────────────

    public function loginForm(): void
    {
        if (!empty($_SESSION['user'])) $this->redirect('/dashboard');
        $this->view('auth/login', ['title' => 'Login | ConsultMee'], 'layouts/auth');
    }

    public function login(): void
    {
        $email    = trim($this->request()->post('email', ''));
        $password = $this->request()->post('password', '');

        if (empty($email) || empty($password)) {
            $this->redirectAuth('/login', 'Please fill in all fields.', 'error');
        }

        $model = new User();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->redirectAuth('/login', 'Invalid email or password.', 'error');
        }

        unset($user['password']);
        $_SESSION['user'] = $user;
        $this->redirect('/dashboard');
    }

    public function signupForm(): void
    {
        if (!empty($_SESSION['user'])) $this->redirect('/dashboard');
        $this->view('auth/signup', ['title' => 'Sign Up | ConsultMee'], 'layouts/auth');
    }

    public function signup(): void
    {
        $data = [
            'full_name' => trim($this->request()->post('full_name', '')),
            'phone'     => trim($this->request()->post('phone', '')),
            'username'  => trim($this->request()->post('username', '')),
            'email'     => trim($this->request()->post('email', '')),
            'password'  => $this->request()->post('password', ''),
            'state'     => $this->request()->post('state', ''),
            'interest1' => $this->request()->post('industry1', ''),
            'interest2' => $this->request()->post('industry2', null),
            'interest3' => $this->request()->post('industry3', null),
            'identity'  => $this->request()->post('identity', ''),
        ];

        $confirmPassword = $this->request()->post('confirm_password', '');

        if (empty($data['full_name']) || empty($data['phone']) || empty($data['username'])
            || empty($data['email']) || empty($data['password']) || empty($data['state'])
            || empty($data['interest1']) || empty($data['identity'])) {
            $this->redirectAuth('/signup', 'Please fill in all required fields.', 'error');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirectAuth('/signup', 'Invalid email format.', 'error');
        }

        if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $this->redirectAuth('/signup', 'Phone number must be exactly 10 digits.', 'error');
        }

        if ($data['password'] !== $confirmPassword) {
            $this->redirectAuth('/signup', 'Passwords do not match.', 'error');
        }

        $model = new User();
        if ($model->exists($data['email'], $data['username'])) {
            $this->redirectAuth('/signup', 'Username or email already registered.', 'error');
        }

        $namePart  = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $data['full_name']), 0, 4));
        $phonePart = substr($data['phone'], -5);
        $data['id']       = $namePart . $phonePart;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $model->create($data);
        $this->redirectAuth('/login', 'Account created successfully. Please log in.', 'success');
    }

    public function forgotPasswordForm(): void
    {
        $this->view('auth/forgot-password', ['title' => 'Forgot Password | ConsultMee'], 'layouts/auth');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
        $this->redirect('/login');
    }

    // ── Consultant Auth ──────────────────────────────────────────────────────

    public function consultantLoginForm(): void
    {
        if (!empty($_SESSION['consultant'])) $this->redirect('/consultant/dashboard');
        $this->view('auth/clogin', ['title' => 'Consultant Login | ConsultMee'], 'layouts/auth');
    }

    public function consultantLogin(): void
    {
        $email    = trim($this->request()->post('email', ''));
        $password = $this->request()->post('password', '');

        if (empty($email) || empty($password)) {
            $this->redirectAuth('/consultant/login', 'Please fill in all fields.', 'error');
        }

        $model      = new Consultant();
        $consultant = $model->findByEmail($email);

        if (!$consultant || !password_verify($password, $consultant['password'])) {
            $this->redirectAuth('/consultant/login', 'Invalid email or password.', 'error');
        }

        unset($consultant['password']);
        $_SESSION['consultant'] = $consultant;
        $this->redirect('/consultant/dashboard');
    }

    public function consultantSignupForm(): void
    {
        if (!empty($_SESSION['consultant'])) $this->redirect('/consultant/dashboard');
        $this->view('auth/csignup', ['title' => 'Consultant Sign Up | ConsultMee'], 'layouts/auth');
    }

    public function consultantSignup(): void
    {
        $data = [
            'name'             => trim($this->request()->post('full_name', '')),
            'username'         => trim($this->request()->post('username', '')),
            'area_of_expertise'=> trim($this->request()->post('industry', '')),
            'state'            => trim($this->request()->post('State', '')),
            'bio'              => trim($this->request()->post('bio', '')),
            'experience'       => trim($this->request()->post('experience', '')),
            'phone'            => trim($this->request()->post('phone', '')),
            'email'            => trim($this->request()->post('email', '')),
            'password'         => $this->request()->post('password', ''),
            'identity'         => $this->request()->post('identity', ''),
        ];

        if (empty($data['name']) || empty($data['username']) || empty($data['area_of_expertise'])
            || empty($data['bio']) || empty($data['experience']) || empty($data['phone'])
            || empty($data['email']) || empty($data['password'])) {
            $this->redirectAuth('/consultant/signup', 'Please fill in all fields.', 'error');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirectAuth('/consultant/signup', 'Invalid email format.', 'error');
        }

        if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $this->redirectAuth('/consultant/signup', 'Phone number must be 10 digits.', 'error');
        }

        $model = new Consultant();
        if ($model->exists($data['email'], $data['username'])) {
            $this->redirectAuth('/consultant/signup', 'Username or email already registered.', 'error');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->create($data);
        $this->redirectAuth('/consultant/login', 'Account created. Please log in.', 'success');
    }

    public function consultantForgotPasswordForm(): void
    {
        $this->view('auth/cforgot-password', ['title' => 'Consultant Forgot Password | ConsultMee'], 'layouts/auth');
    }

    public function consultantLogout(): void
    {
        unset($_SESSION['consultant']);
        session_destroy();
        $this->redirect('/consultant/login');
    }

    // ── Private ──────────────────────────────────────────────────────────────

    private function redirectAuth(string $path, string $msg, string $type): never
    {
        $this->redirect($path . '?msg=' . urlencode($msg) . '&type=' . $type);
    }
}
