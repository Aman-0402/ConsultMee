<?php
declare(strict_types=1);

namespace ConsultMee\Controllers\Api;

use ConsultMee\Core\Controller;
use ConsultMee\Models\Project;
use ConsultMee\Models\Consultant;
use ConsultMee\Services\MailService;

class ProjectApiController extends Controller
{
    public function list(): void
    {
        $user = $this->user();
        if (empty($user['username'])) {
            $this->json(['success' => false, 'message' => 'not_authenticated']);
            return;
        }

        $req     = $this->request();
        $filter  = ($req->get('filter', '') === 'history') ? 'history' : 'active';
        $page    = max(1, (int)$req->get('page', 1));
        $perPage = min(50, max(5, (int)$req->get('per_page', 10)));

        $model  = new Project();
        $result = $model->list($filter, $user['username'], $page, $perPage);

        $this->json([
            'success'  => true,
            'filter'   => $filter,
            'page'     => $page,
            'per_page' => $perPage,
            'total'    => $result['total'],
            'projects' => $result['projects'],
        ]);
    }

    public function create(): void
    {
        $user = $this->user();
        if (empty($user['username'])) {
            $this->json(['success' => false, 'message' => 'not_authenticated']);
            return;
        }

        $req   = $this->request();
        $title = trim($req->post('title', ''));
        $desc  = trim($req->post('description', ''));

        if ($title === '' || $desc === '') {
            $this->json(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        $data = [
            'username'          => $user['username'],
            'title'             => $title,
            'short_description' => trim($req->post('short_description', '')),
            'description'       => $desc,
            'budget'            => trim($req->post('budget', '')),
            'billing_cycle'     => trim($req->post('billing', '')),
            'links'             => trim($req->post('links', '')),
            'expiry_date'       => trim($req->post('expiry_date', '')) ?: null,
        ];

        $model     = new Project();
        $projectId = $model->create($data);

        $cModel      = new Consultant();
        $consultants = $cModel->allEmails();

        $mail       = new MailService();
        $mailSent   = 0;
        $mailFailed = 0;
        $subject    = 'New Project Alert - ConsultMee';

        foreach ($consultants as $c) {
            $body = "<h2>New Project: " . htmlspecialchars($title) . "</h2>"
                  . "<p>Hi <strong>" . htmlspecialchars($c['name']) . "</strong>,</p>"
                  . "<p>A new project has been posted on ConsultMee. Log in to apply.</p>"
                  . "<p><strong>Budget:</strong> ₹" . htmlspecialchars($data['budget']) . " | "
                  . "<strong>Billing:</strong> " . htmlspecialchars($data['billing_cycle']) . "</p>";
            $mail->send($c['email'], $subject, $body) ? $mailSent++ : $mailFailed++;
        }

        $this->json([
            'success'      => true,
            'message'      => 'Project created',
            'project_id'   => $projectId,
            'mail_sent'    => $mailSent,
            'mail_failed'  => $mailFailed,
        ]);
    }

    public function applications(): void
    {
        $user = $this->user();
        if (empty($user['username'])) {
            $this->json(['success' => false, 'message' => 'not_authenticated']);
            return;
        }

        $projectId = trim($this->request()->get('project_id', ''));
        if ($projectId === '') {
            $this->json(['success' => false, 'message' => 'missing_project_id']);
            return;
        }

        $model        = new Project();
        $applications = $model->getApplications($projectId);

        $this->json([
            'success'      => true,
            'project_id'   => $projectId,
            'applications' => $applications,
        ]);
    }

    public function apply(): void
    {
        $c = $this->consultant();
        if (empty($c['username'])) {
            $this->json(['success' => false, 'error' => 'not_authenticated']);
            return;
        }

        $data          = $this->request()->json();
        $projectId     = substr(trim($data['project_id'] ?? ''), 0, 20);
        $userMsg       = substr(trim($data['user_msg'] ?? ''), 0, 150);
        $portfolioLink = substr(trim($data['portfolio_link'] ?? ''), 0, 100);

        if (!$projectId || !$userMsg) {
            $this->json(['success' => false, 'error' => 'project_id and user_msg required']);
            return;
        }

        $model   = new Project();
        $applied = $model->apply($projectId, $c['username'], $userMsg, $portfolioLink);

        if (!$applied) {
            $this->json(['success' => false, 'error' => 'Already applied']);
            return;
        }

        $project = $model->findWithUploader($projectId);
        if (!$project) {
            $this->json(['success' => false, 'error' => 'Project not found']);
            return;
        }

        $cModel = new \ConsultMee\Models\Consultant();
        $cData  = $cModel->findByUsername($c['username']);

        if ($cData) {
            $mail    = new MailService();
            $subject = 'Application Submitted – ConsultMee';
            $mail->send($cData['email'], $subject,
                "<p>Hi <strong>{$cData['name']}</strong>, you have applied for: <strong>{$project['title']}</strong>.</p>"
            );
            $mail->send($project['uploader_email'], 'New Project Application – ConsultMee',
                "<p><strong>{$cData['name']}</strong> applied for <strong>{$project['title']}</strong>.</p>"
                . "<p><strong>Message:</strong> " . htmlspecialchars($userMsg) . "</p>"
            );
        }

        $this->json(['success' => true, 'message' => 'Application submitted successfully']);
    }
}
