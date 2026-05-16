<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class PasswordReset extends Model
{
    public function find(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT otp_hash, expires_at FROM password_resets WHERE email=? LIMIT 1',
            [$email]
        );
    }

    public function upsert(string $email, string $otpHash, int $expiresAt): void
    {
        $existing = $this->find($email);
        if ($existing) {
            $this->execute(
                'UPDATE password_resets SET otp_hash=?, expires_at=? WHERE email=?',
                [$otpHash, $expiresAt, $email]
            );
        } else {
            $this->execute(
                'INSERT INTO password_resets (email, otp_hash, expires_at) VALUES (?, ?, ?)',
                [$email, $otpHash, $expiresAt]
            );
        }
    }

    public function delete(string $email): void
    {
        $this->execute('DELETE FROM password_resets WHERE email=?', [$email]);
    }
}
