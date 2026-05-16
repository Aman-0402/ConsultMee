<?php
declare(strict_types=1);

namespace ConsultMee\Controllers\Api;

use ConsultMee\Core\Controller;
use ConsultMee\Models\Consultant;
use ConsultMee\Models\Appointment;
use ConsultMee\Models\Project;
use ConsultMee\Services\UploadService;
use ConsultMee\Services\MailService;

class ConsultantApiController extends Controller
{
    public function getProfile(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['error' => 'Unauthorized']); return; }

        $model = new Consultant();
        $row   = $model->withRatings($c['username']);
        if (!$row) { $this->json(['error' => 'Consultant profile not found']); return; }

        $this->json([
            'username'         => $row['username'],
            'name'             => $row['name'],
            'email'            => $row['email'],
            'phone'            => $row['phone'],
            'area_of_expertise'=> $row['area_of_expertise'],
            'bio'              => $row['bio'],
            'experience'       => $row['experience'],
            'state'            => $row['state'],
            'hourly_rate'      => $row['hourly_rate'],
            'identity'         => $row['identity'],
            'profile_img'      => $row['profile_img'],
            'avg_rating'       => (float)$row['avg_rating'],
            'total_reviews'    => (int)$row['total_reviews'],
        ]);
    }

    public function updateProfile(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['error' => 'Unauthorized']); return; }

        $req      = $this->request();
        $username = $c['username'];

        $data = [
            'name'              => $req->post('name', ''),
            'phone'             => $req->post('phone', ''),
            'area_of_expertise' => $req->post('area_of_expertise', ''),
            'bio'               => $req->post('bio', ''),
            'experience'        => $req->post('experience', ''),
            'hourly_rate'       => $req->post('rate', ''),
            'state'             => $req->post('state', ''),
            'identity'          => $req->post('identity', ''),
        ];

        $file = $req->file('profile_image');
        if ($file) {
            $model    = new Consultant();
            $oldImg   = ($model->findByUsername($username))['profile_img'] ?? null;
            $uploader = new UploadService();
            try {
                $filename = $uploader->upload($file, 'consultants');
                if ($oldImg) $uploader->delete($oldImg, 'consultants');
                $data['profile_img'] = $filename;
            } catch (\RuntimeException $e) {
                $this->json(['error' => $e->getMessage()]);
                return;
            }
        }

        $model = new Consultant();
        $model->update($username, $data);
        $this->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    public function appointmentRequests(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['error' => 'Unauthorized']); return; }

        $model = new Appointment();
        $this->json($model->findByConsultant($c['username']));
    }

    public function confirmedAppointments(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['error' => 'Unauthorized']); return; }

        $model = new Appointment();
        $rows  = $model->confirmedByConsultant($c['username']);
        foreach ($rows as &$row) {
            $row['profile_img'] = $row['profile_img'] ?? 'default.png';
            $row['users_name']  = $row['user_full_name'] ?? $row['users_name'];
        }
        $this->json($rows);
    }

    public function appointmentHistory(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['error' => 'Unauthorized']); return; }

        $model = new Appointment();
        $this->json($model->historyByConsultant($c['username']));
    }

    public function updateAppointment(): void
    {
        $c = $this->consultant();
        if (!$c) {
            http_response_code(401);
            $this->json(['success' => false, 'error' => 'Unauthorized']);
            return;
        }

        $data = $this->request()->json();

        foreach (['id', 'date', 'time', 'mode', 'status', 'meeting_link', 'consultant_message'] as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                $this->json(['success' => false, 'error' => "Missing field: $field"]);
                return;
            }
        }

        $data['status'] = strtolower($data['status']);

        $model  = new Appointment();
        $updated = $model->updateByConsultant($data['id'], $c['username'], $data);

        if (!$updated) {
            $this->json(['success' => false, 'error' => 'No changes made or invalid appointment ID']);
            return;
        }

        $this->json(['success' => true]);

        $appt = $model->findById($data['id']);
        if ($appt) {
            $userModel = new \ConsultMee\Models\User();
            $userData  = $userModel->findByUsername($appt['user_username']);
            if ($userData) {
                $mail    = new MailService();
                $subject = 'Appointment Updated - ConsultMee';
                $body    = "<p>Hi <strong>{$userData['full_name']}</strong>,</p>"
                         . "<p>Your appointment with <strong>{$c['username']}</strong> has been <strong>updated</strong>.</p>"
                         . "<p><strong>Date:</strong> {$data['date']} | <strong>Time:</strong> {$data['time']}</p>"
                         . "<p><strong>Mode:</strong> {$data['mode']} | <strong>Status:</strong> {$data['status']}</p>"
                         . "<p><strong>Meeting Link:</strong> <a href='{$data['meeting_link']}'>{$data['meeting_link']}</a></p>"
                         . "<p><strong>Message:</strong> {$data['consultant_message']}</p>";
                $mail->send($userData['email'], $subject, $body);
            }
        }
    }

    public function browseProjects(): void
    {
        $c = $this->consultant();
        if (!$c) { $this->json(['success' => false, 'message' => 'not_authenticated']); return; }

        $req     = $this->request();
        $page    = max(1, (int)$req->get('page', 1));
        $perPage = min(50, max(5, (int)$req->get('per_page', 10)));
        $query   = trim($req->get('q', ''));

        $model  = new Project();
        $result = $model->listForConsultant($query, $page, $perPage);

        $this->json([
            'success'  => true,
            'page'     => $page,
            'per_page' => $perPage,
            'total'    => $result['total'],
            'projects' => $result['projects'],
        ]);
    }
}
