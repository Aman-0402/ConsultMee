<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT id, full_name, username, phone, email, interest1, interest2, interest3,
                    password, state, identity, profile_img
             FROM users WHERE email = ? LIMIT 1',
            [$email]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->fetchOne(
            'SELECT id, full_name, username, phone, email, interest1, interest2, interest3,
                    state, identity, profile_img
             FROM users WHERE username = ? LIMIT 1',
            [$username]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT id, full_name, username, phone, email, interest1, interest2, interest3,
                    state, identity, profile_img
             FROM users WHERE id = ? LIMIT 1',
            [$id]
        );
    }

    public function exists(string $email, string $username): bool
    {
        $row = $this->fetchOne(
            'SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1',
            [$username, $email]
        );
        return $row !== null;
    }

    public function create(array $data): int
    {
        $this->execute(
            'INSERT INTO users (id, full_name, username, phone, email, password, state,
                                interest1, interest2, interest3, identity)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['id'],
                $data['full_name'],
                $data['username'],
                $data['phone'],
                $data['email'],
                $data['password'],
                $data['state'],
                $data['interest1'],
                $data['interest2'] ?? null,
                $data['interest3'] ?? null,
                $data['identity'],
            ]
        );
        return (int)$this->lastInsertId();
    }

    public function update(string $username, array $data): bool
    {
        if (!empty($data['profile_img'])) {
            return (bool)$this->execute(
                'UPDATE users SET full_name=?, phone=?, state=?, interest1=?, interest2=?,
                                  interest3=?, profile_img=?, identity=?
                 WHERE username=?',
                [
                    $data['full_name'], $data['phone'], $data['state'],
                    $data['interest1'], $data['interest2'] ?? null, $data['interest3'] ?? null,
                    $data['profile_img'], $data['identity'], $username,
                ]
            );
        }
        return (bool)$this->execute(
            'UPDATE users SET full_name=?, phone=?, state=?, interest1=?, interest2=?,
                              interest3=?, identity=?
             WHERE username=?',
            [
                $data['full_name'], $data['phone'], $data['state'],
                $data['interest1'], $data['interest2'] ?? null, $data['interest3'] ?? null,
                $data['identity'], $username,
            ]
        );
    }

    public function updatePassword(string $email, string $hashedPassword): bool
    {
        return (bool)$this->execute(
            'UPDATE users SET password=? WHERE email=?',
            [$hashedPassword, $email]
        );
    }
}
