<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class Rating extends Model
{
    public function insertIgnore(string $appointmentId, string $consultantUsername,
                                  string $userUsername, int $rating, string $feedback): bool
    {
        return (bool)$this->execute(
            'INSERT IGNORE INTO consultant_ratings
             (appointment_id, consultant_username, user_username, rating, feedback)
             VALUES (?, ?, ?, ?, ?)',
            [$appointmentId, $consultantUsername, $userUsername, $rating, $feedback]
        );
    }

    public function getByConsultant(string $consultantUsername): array
    {
        return $this->fetchAll(
            'SELECT user_username, rating, feedback, created_at
             FROM consultant_ratings
             WHERE consultant_username=?
             ORDER BY created_at DESC',
            [$consultantUsername]
        );
    }
}
