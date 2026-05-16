<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class Consultant extends Model
{
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT id, name, username, area_of_expertise, bio, experience, phone, email,
                    password, State AS state, hourly_rate, identity, profile_img
             FROM consultants WHERE email = ? LIMIT 1',
            [$email]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return $this->fetchOne(
            'SELECT id, name, username, area_of_expertise, bio, experience, phone, email,
                    State AS state, hourly_rate, identity, profile_img
             FROM consultants WHERE username = ? LIMIT 1',
            [$username]
        );
    }

    public function exists(string $email, string $username): bool
    {
        $row = $this->fetchOne(
            'SELECT id FROM consultants WHERE username = ? OR email = ? LIMIT 1',
            [$username, $email]
        );
        return $row !== null;
    }

    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO consultants (name, username, area_of_expertise, bio, experience, phone,
                                      email, password, State, identity)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['name'], $data['username'], $data['area_of_expertise'],
                $data['bio'], $data['experience'], $data['phone'],
                $data['email'], $data['password'], $data['state'], $data['identity'],
            ]
        );
    }

    public function update(string $username, array $data): bool
    {
        if (!empty($data['profile_img'])) {
            return (bool)$this->execute(
                'UPDATE consultants SET name=?, phone=?, area_of_expertise=?, bio=?, experience=?,
                                       State=?, hourly_rate=?, profile_img=?, identity=?
                 WHERE username=?',
                [
                    $data['name'], $data['phone'], $data['area_of_expertise'],
                    $data['bio'], $data['experience'], $data['state'],
                    $data['hourly_rate'], $data['profile_img'], $data['identity'], $username,
                ]
            );
        }
        return (bool)$this->execute(
            'UPDATE consultants SET name=?, phone=?, area_of_expertise=?, bio=?, experience=?,
                                   State=?, hourly_rate=?, identity=?
             WHERE username=?',
            [
                $data['name'], $data['phone'], $data['area_of_expertise'],
                $data['bio'], $data['experience'], $data['state'],
                $data['hourly_rate'], $data['identity'], $username,
            ]
        );
    }

    public function updatePassword(string $email, string $hashedPassword): bool
    {
        return (bool)$this->execute(
            'UPDATE consultants SET password=? WHERE email=?',
            [$hashedPassword, $email]
        );
    }

    public function withRatings(string $username): ?array
    {
        return $this->fetchOne(
            'SELECT c.username, c.name, c.email, c.phone, c.area_of_expertise, c.bio,
                    c.experience, c.State AS state, c.hourly_rate, c.identity, c.profile_img,
                    COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating,
                    COUNT(r.id) AS total_reviews
             FROM consultants c
             LEFT JOIN consultant_ratings r ON r.consultant_username = c.username
             WHERE c.username = ?
             GROUP BY c.username, c.name, c.email, c.phone, c.area_of_expertise, c.bio,
                      c.experience, c.State, c.hourly_rate, c.identity, c.profile_img
             LIMIT 1',
            [$username]
        );
    }

    public function listByInterests(string $i1, string $i2, string $i3): array
    {
        return $this->fetchAll(
            'SELECT c.username, c.name, c.area_of_expertise, c.bio, c.experience,
                    c.State AS state, c.hourly_rate, c.identity, c.profile_img,
                    ROUND(AVG(r.rating), 1) AS avg_rating,
                    COUNT(r.id) AS total_reviews
             FROM consultants c
             LEFT JOIN consultant_ratings r ON c.username = r.consultant_username
             WHERE c.area_of_expertise = ? OR c.area_of_expertise = ? OR c.area_of_expertise = ?
             GROUP BY c.username',
            [$i1, $i2, $i3]
        );
    }

    public function listByExpertise(string $category): array
    {
        return $this->fetchAll(
            'SELECT name, TRIM(username) AS username, area_of_expertise, bio, experience,
                    State AS state, hourly_rate, profile_img
             FROM consultants WHERE area_of_expertise = ?',
            [$category]
        );
    }

    public function allEmails(): array
    {
        return $this->fetchAll(
            "SELECT name, email FROM consultants WHERE email IS NOT NULL AND email != ''"
        );
    }
}
