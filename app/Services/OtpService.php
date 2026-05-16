<?php
declare(strict_types=1);

namespace ConsultMee\Services;

use ConsultMee\Models\PasswordReset;

class OtpService
{
    private PasswordReset $model;
    private MailService $mail;

    public function __construct()
    {
        $this->model = new PasswordReset();
        $this->mail  = new MailService();
    }

    public function generate(): string
    {
        return (string)random_int(100000, 999999);
    }

    public function store(string $email, string $otp): void
    {
        $hash      = password_hash($otp, PASSWORD_DEFAULT);
        $expiresAt = time() + 600;
        $this->model->upsert($email, $hash, $expiresAt);
    }

    public function verify(string $email, string $otp): bool
    {
        $record = $this->model->find($email);
        if (!$record) return false;
        if (time() > (int)$record['expires_at']) return false;
        return password_verify($otp, $record['otp_hash']);
    }

    public function delete(string $email): void
    {
        $this->model->delete($email);
    }
}
