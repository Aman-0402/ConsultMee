<?php
declare(strict_types=1);

namespace ConsultMee\Controllers\Api;

use ConsultMee\Core\Controller;
use ConsultMee\Models\Appointment;
use ConsultMee\Models\Consultant;
use ConsultMee\Models\User;
use ConsultMee\Models\Rating;
use ConsultMee\Services\MailService;

class AppointmentApiController extends Controller
{
    public function book(): void
    {
        header('Content-Type: application/json');
        $data = $this->request()->json();

        $required = ['consultant_name','users_name','consultant_username','user_username',
                     'appointment_date','appointment_time','mode_of_appointment'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                $this->json(['success' => false, 'message' => "Missing field: $field"]);
                return;
            }
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['appointment_date'])) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD.']);
            return;
        }

        if (!preg_match('/^\d{2}:\d{2}$/', $data['appointment_time'])) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'Invalid time format. Use HH:MM.']);
            return;
        }

        $model = new Appointment();
        if ($model->checkDuplicate(
            $data['consultant_username'], $data['user_username'],
            $data['appointment_date'], $data['appointment_time']
        )) {
            $this->json(['success' => false, 'message' => 'You have already requested this appointment slot.']);
            return;
        }

        $cModel     = new Consultant();
        $consultant = $cModel->findByUsername($data['consultant_username']);
        if (!$consultant) {
            http_response_code(404);
            $this->json(['success' => false, 'message' => 'Consultant not found.']);
            return;
        }

        $uModel = new User();
        $user   = $uModel->findByUsername($data['user_username']);
        if (!$user) {
            http_response_code(404);
            $this->json(['success' => false, 'message' => 'User not found.']);
            return;
        }

        $appointmentId = bin2hex(random_bytes(6));
        $model->create([
            'appointment_id'      => $appointmentId,
            'consultant_name'     => trim($data['consultant_name']),
            'users_name'          => trim($data['users_name']),
            'consultant_username' => $data['consultant_username'],
            'user_username'       => $data['user_username'],
            'appointment_date'    => $data['appointment_date'],
            'appointment_time'    => $data['appointment_time'],
            'mode_of_appointment' => $data['mode_of_appointment'],
            'user_request'        => $data['user_request'] ?? null,
        ]);

        $mail    = new MailService();
        $date    = $data['appointment_date'];
        $time    = $data['appointment_time'];
        $mode    = $data['mode_of_appointment'];
        $request = !empty($data['user_request'])
            ? nl2br(htmlspecialchars($data['user_request']))
            : 'No specific request provided.';

        $mailConsultant = $mail->send(
            $consultant['email'],
            'New Appointment Booking Received - ConsultMee',
            "<h2>New Appointment Request</h2>"
            . "<p>Dear <strong>{$consultant['name']}</strong>,</p>"
            . "<p>You have a new appointment request from <strong>{$user['full_name']}</strong>.</p>"
            . "<p><strong>ID:</strong> $appointmentId | <strong>Date:</strong> $date | <strong>Time:</strong> $time | <strong>Mode:</strong> $mode</p>"
            . "<p><strong>Request:</strong> $request</p>"
        );

        $mailUser = $mail->send(
            $user['email'],
            'Appointment Request Submitted - ConsultMee',
            "<h2>Appointment Submitted!</h2>"
            . "<p>Hi <strong>{$user['full_name']}</strong>,</p>"
            . "<p>Your appointment request has been sent to <strong>{$consultant['name']}</strong>.</p>"
            . "<p><strong>ID:</strong> $appointmentId | <strong>Date:</strong> $date | <strong>Time:</strong> $time | <strong>Mode:</strong> $mode</p>"
        );

        $this->json([
            'success'         => true,
            'appointment_id'  => $appointmentId,
            'message'         => 'Appointment booked successfully. Notifications sent.',
            'consultant_mail' => $mailConsultant,
            'user_mail'       => $mailUser,
        ]);
    }

    public function userAppointments(): void
    {
        $user = $this->user();
        if (!$user) { $this->json(['error' => 'User not logged in']); return; }

        $model = new Appointment();
        $this->json($model->findByUser($user['username']));
    }

    public function userHistory(): void
    {
        $user = $this->user();
        if (!$user) { $this->json(['error' => 'User not logged in']); return; }

        $model = new Appointment();
        $this->json($model->historyByUser($user['username']));
    }

    public function updateStatus(): void
    {
        $data = $this->request()->json();

        if (!isset($data['appointment_id'], $data['new_status'])) {
            http_response_code(400);
            $this->json(['success' => false, 'message' => 'Missing required parameters.']);
            return;
        }

        $appointmentId = trim($data['appointment_id']);
        $newStatus     = strtolower(trim($data['new_status']));

        if (!in_array($newStatus, ['pending','confirmed','cancelled','completed'], true)) {
            $this->json(['success' => false, 'message' => 'Invalid status value.']);
            return;
        }

        $model = new Appointment();
        $appt  = $model->findById($appointmentId);
        if (!$appt) {
            $this->json(['success' => false, 'message' => 'No matching appointment found.']);
            return;
        }

        $uModel = new User();
        $cModel = new Consultant();
        $user   = $uModel->findByUsername($appt['user_username']);
        $consultant = $cModel->findByUsername($appt['consultant_username']);

        if (!$user || !$consultant) {
            $this->json(['success' => false, 'message' => 'User or consultant not found.']);
            return;
        }

        $updated = $model->updateStatus($appointmentId, $newStatus);
        if (!$updated) {
            $this->json(['success' => false, 'message' => 'No matching appointment found or database error.']);
            return;
        }

        $this->json(['success' => true, 'message' => 'Appointment status updated.']);

        if ($newStatus === 'cancelled') {
            $mail    = new MailService();
            $subject = 'Appointment Cancelled - ConsultMee';
            $mail->send($user['email'], $subject,
                "<p>Hi <strong>{$user['full_name']}</strong>, your appointment (ID: $appointmentId) with {$consultant['name']} has been cancelled.</p>"
            );
            $mail->send($consultant['email'], $subject,
                "<p>Hi <strong>{$consultant['name']}</strong>, the appointment (ID: $appointmentId) with {$user['full_name']} has been cancelled.</p>"
            );
        }
    }

    public function rate(): void
    {
        $req           = $this->request();
        $appointmentId = $req->post('appointment_id', '');
        $consultantUsername = $req->post('consultant_username', '');
        $userUsername  = $req->post('user_username', '');
        $rating        = (int)$req->post('rating', 0);
        $feedback      = $req->post('feedback', '');

        $model   = new Rating();
        $success = $model->insertIgnore($appointmentId, $consultantUsername, $userUsername, $rating, $feedback);

        $this->json($success
            ? ['status' => 'success']
            : ['status' => 'error', 'message' => 'Could not save rating']
        );
    }
}
