<?php
declare(strict_types=1);

namespace ConsultMee\Controllers\Api;

use ConsultMee\Core\Controller;
use ConsultMee\Models\User;
use ConsultMee\Models\Consultant;
use ConsultMee\Services\OtpService;
use ConsultMee\Services\MailService;

class AuthApiController extends Controller
{
    // ── User OTP ────────────────────────────────────────────────────────────

    public function sendOtp(): void
    {
        $email = trim($this->request()->post('email', ''));
        if (empty($email)) {
            $this->json(['error' => 'Email required']);
            return;
        }

        $model = new User();
        if (!$model->findByEmail($email)) {
            $this->json(['error' => 'Email not found in database']);
            return;
        }

        $otpService = new OtpService();
        $otp        = $otpService->generate();
        $otpService->store($email, $otp);

        $mail = new MailService();
        $sent = $mail->send(
            $email,
            'Your ConsultMee Password Reset OTP',
            "<p>Your OTP for password reset is: <strong>$otp</strong></p><p>It will expire in 10 minutes.</p>"
        );

        if ($sent) {
            $this->json(['success' => 'OTP sent successfully to your registered email.']);
        } else {
            $this->json(['error' => 'Failed to send OTP email. Check mail configuration.']);
        }
    }

    public function verifyOtp(): void
    {
        $email       = trim($this->request()->post('email', ''));
        $otp         = trim($this->request()->post('otp', ''));
        $newPassword = trim($this->request()->post('new_password', ''));

        if (empty($email) || empty($otp) || empty($newPassword)) {
            $this->json(['error' => 'All fields are required.']);
            return;
        }

        $otpService = new OtpService();
        if (!$otpService->verify($email, $otp)) {
            $this->json(['error' => 'Invalid or expired OTP.']);
            return;
        }

        $model = new User();
        $model->updatePassword($email, password_hash($newPassword, PASSWORD_DEFAULT));
        $otpService->delete($email);

        $this->json(['success' => 'Password reset successfully. You can now log in.']);
    }

    // ── Consultant OTP ───────────────────────────────────────────────────────

    public function consultantSendOtp(): void
    {
        $email = trim($this->request()->post('email', ''));
        if (empty($email)) {
            $this->json(['error' => 'Email required']);
            return;
        }

        $model = new Consultant();
        if (!$model->findByEmail($email)) {
            $this->json(['error' => 'Email not found in consultant database']);
            return;
        }

        $otpService = new OtpService();
        $otp        = $otpService->generate();
        $otpService->store($email, $otp);

        $mail = new MailService();
        $sent = $mail->send(
            $email,
            'ConsultMee Consultant Password Reset OTP',
            "<p>Your OTP for password reset is: <strong>$otp</strong></p><p>It will expire in 10 minutes.</p>"
        );

        if ($sent) {
            $this->json(['success' => 'OTP sent successfully to your registered email.']);
        } else {
            $this->json(['error' => 'Failed to send OTP email.']);
        }
    }

    public function consultantVerifyOtp(): void
    {
        $email       = trim($this->request()->post('email', ''));
        $otp         = trim($this->request()->post('otp', ''));
        $newPassword = trim($this->request()->post('new_password', ''));

        if (empty($email) || empty($otp) || empty($newPassword)) {
            $this->json(['error' => 'All fields are required.']);
            return;
        }

        $otpService = new OtpService();
        if (!$otpService->verify($email, $otp)) {
            $this->json(['error' => 'Invalid or expired OTP.']);
            return;
        }

        $model = new Consultant();
        $model->updatePassword($email, password_hash($newPassword, PASSWORD_DEFAULT));
        $otpService->delete($email);

        $this->json(['success' => 'Password reset successfully. You can now log in.']);
    }
}
