<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class Appointment extends Model
{
    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO appointments
             (appointment_id, consultant_name, users_name, consultant_username, user_username,
              status, appointment_date, appointment_time, mode_of_appointment, user_request)
             VALUES (?, ?, ?, ?, ?, \'pending\', ?, ?, ?, ?)',
            [
                $data['appointment_id'], $data['consultant_name'], $data['users_name'],
                $data['consultant_username'], $data['user_username'],
                $data['appointment_date'], $data['appointment_time'],
                $data['mode_of_appointment'], $data['user_request'] ?? null,
            ]
        );
    }

    public function checkDuplicate(string $consultantUsername, string $userUsername,
                                   string $date, string $time): bool
    {
        $row = $this->fetchOne(
            "SELECT appointment_id FROM appointments
             WHERE consultant_username=? AND user_username=?
               AND appointment_date=? AND appointment_time=?
               AND status IN ('pending','confirmed')
             LIMIT 1",
            [$consultantUsername, $userUsername, $date, $time]
        );
        return $row !== null;
    }

    public function findByUser(string $username): array
    {
        return $this->fetchAll(
            "SELECT appointment_id, consultant_name, appointment_date, appointment_time,
                    status, user_request, consultant_message, meeting_link
             FROM appointments
             WHERE user_username=? AND status != 'cancelled'
             ORDER BY appointment_date DESC, appointment_time DESC",
            [$username]
        );
    }

    public function historyByUser(string $username): array
    {
        return $this->fetchAll(
            'SELECT a.*, r.rating AS user_rating, r.feedback,
                    CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END AS rating_given
             FROM appointments a
             LEFT JOIN consultant_ratings r
               ON a.appointment_id = r.appointment_id AND a.user_username = r.user_username
             WHERE a.user_username = ?
             ORDER BY a.appointment_date DESC',
            [$username]
        );
    }

    public function findByConsultant(string $username): array
    {
        return $this->fetchAll(
            "SELECT a.appointment_id, a.users_name, a.appointment_date, a.appointment_time,
                    a.mode_of_appointment, a.status, a.meeting_link, a.user_request,
                    a.consultant_message, u.profile_img
             FROM appointments a
             JOIN users u
               ON a.user_username COLLATE utf8mb4_general_ci = u.username COLLATE utf8mb4_general_ci
             WHERE a.consultant_username COLLATE utf8mb4_general_ci = ?
               AND a.status = 'pending'
             ORDER BY a.appointment_date ASC",
            [$username]
        );
    }

    public function confirmedByConsultant(string $username): array
    {
        return $this->fetchAll(
            'SELECT a.*, u.profile_img
             FROM appointments a
             JOIN users u
               ON a.user_username COLLATE utf8mb4_general_ci = u.username COLLATE utf8mb4_general_ci
             WHERE a.consultant_username COLLATE utf8mb4_general_ci = ?
               AND a.status = \'confirmed\'',
            [$username]
        );
    }

    public function historyByConsultant(string $username): array
    {
        return $this->fetchAll(
            "SELECT appointment_id, users_name, appointment_date, appointment_time,
                    mode_of_appointment, status, meeting_link, user_request, consultant_message
             FROM appointments
             WHERE consultant_username=? AND status IN ('completed','cancelled')
             ORDER BY appointment_date DESC",
            [$username]
        );
    }

    public function updateByConsultant(string $appointmentId, string $consultantUsername,
                                       array $data): bool
    {
        return (bool)$this->execute(
            'UPDATE appointments
             SET appointment_date=?, appointment_time=?, mode_of_appointment=?,
                 status=?, meeting_link=?, consultant_message=?
             WHERE appointment_id=? AND consultant_username=?',
            [
                $data['date'], $data['time'], $data['mode'], $data['status'],
                $data['meeting_link'], $data['consultant_message'],
                $appointmentId, $consultantUsername,
            ]
        );
    }

    public function updateStatus(string $appointmentId, string $status): bool
    {
        return (bool)$this->execute(
            'UPDATE appointments SET status=? WHERE appointment_id=?',
            [$status, $appointmentId]
        );
    }

    public function findById(string $appointmentId): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM appointments WHERE appointment_id=? LIMIT 1',
            [$appointmentId]
        );
    }
}
