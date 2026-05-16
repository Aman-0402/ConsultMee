<?php
declare(strict_types=1);

namespace ConsultMee\Controllers\Api;

use ConsultMee\Core\Controller;
use ConsultMee\Models\Consultant;
use ConsultMee\Models\Category;
use ConsultMee\Models\User;
use ConsultMee\Services\UploadService;

class UserApiController extends Controller
{
    public function listByInterests(): void
    {
        $user = $this->user();
        if (!$user) {
            $this->json(['error' => 'User not logged in']);
            return;
        }
        $model = new Consultant();
        $rows  = $model->listByInterests(
            $user['interest1'] ?? '',
            $user['interest2'] ?? '',
            $user['interest3'] ?? ''
        );
        $this->json($rows);
    }

    public function categories(): void
    {
        $model      = new Category();
        $categories = $model->listWithConsultants();
        if (empty($categories)) {
            $this->json(['message' => 'No categories with consultants found']);
        } else {
            $this->json($categories);
        }
    }

    public function categoryConsultants(): void
    {
        $req      = $this->request();
        $category = trim($req->get('category', ''));
        if (empty($category)) {
            $this->json(['error' => 'Category not specified']);
            return;
        }
        $model = new Consultant();
        $this->json($model->listByExpertise($category));
    }

    public function getProfile(): void
    {
        $user = $this->user();
        if (!$user) {
            $this->json(['error' => 'User not authenticated']);
            return;
        }
        $model  = new User();
        $record = $model->findByUsername($user['username']);
        if (!$record) {
            $this->json(['error' => 'User not found']);
            return;
        }
        $img = !empty($record['profile_img'])
            ? UPLOAD_URL_USERS . $record['profile_img']
            : UPLOAD_URL_USERS . 'default.png';

        $this->json([
            'full_name'  => $record['full_name'],
            'username'   => $record['username'],
            'phone'      => $record['phone'],
            'email'      => $record['email'],
            'interest1'  => $record['interest1'],
            'interest2'  => $record['interest2'],
            'interest3'  => $record['interest3'],
            'state'      => $record['state'],
            'identity'   => $record['identity'],
            'profile_img'=> $img,
        ]);
    }

    public function updateProfile(): void
    {
        $user = $this->user();
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $req      = $this->request();
        $username = $user['username'];

        $data = [
            'full_name' => trim($req->post('full_name', '')),
            'identity'  => $req->post('identity', ''),
            'phone'     => trim($req->post('phone', '')),
            'state'     => $req->post('state', ''),
            'interest1' => $req->post('interest1', ''),
            'interest2' => $req->post('interest2', null),
            'interest3' => $req->post('interest3', null),
        ];

        $file = $req->file('profile_image');
        if ($file) {
            $model    = new User();
            $oldImg   = ($model->findByUsername($username))['profile_img'] ?? null;
            $uploader = new UploadService();
            try {
                $filename = $uploader->upload($file, 'users');
                if ($oldImg) $uploader->delete($oldImg, 'users');
                $data['profile_img'] = $filename;
            } catch (\RuntimeException $e) {
                $this->json(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
        }

        $model = new User();
        $model->update($username, $data);
        $this->json(['success' => true, 'message' => 'User profile updated']);
    }
}
