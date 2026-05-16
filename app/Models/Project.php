<?php
declare(strict_types=1);

namespace ConsultMee\Models;

use ConsultMee\Core\Model;

class Project extends Model
{
    public function getUniqueId(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $id  = bin2hex(random_bytes(10));
            $row = $this->fetchOne('SELECT 1 FROM projects WHERE project_id=? LIMIT 1', [$id]);
            if ($row === null) return $id;
        }
        throw new \RuntimeException('Failed to generate unique project_id');
    }

    public function create(array $data): string
    {
        $id = $this->getUniqueId();
        $this->execute(
            'INSERT INTO projects
             (project_id, username, title, short_description, description, budget,
              billing_cycle, links, posted_at, expiry_date, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), NOW())',
            [
                $id, $data['username'], $data['title'], $data['short_description'],
                $data['description'], $data['budget'], $data['billing_cycle'],
                $data['links'] ?? '', $data['expiry_date'] ?? null,
            ]
        );
        return $id;
    }

    public function list(string $filter, string $username, int $page, int $perPage): array
    {
        if ($filter === 'history') {
            $where = 'WHERE p.expiry_date IS NOT NULL AND p.expiry_date < CURDATE() AND p.username=?';
        } else {
            $where = 'WHERE (p.expiry_date IS NULL OR p.expiry_date >= CURDATE()) AND p.username=?';
        }

        $total = (int)($this->fetchOne(
            "SELECT COUNT(*) AS cnt FROM projects p $where", [$username]
        )['cnt'] ?? 0);

        if ($total === 0) return ['total' => 0, 'projects' => []];

        $offset   = ($page - 1) * $perPage;
        $projects = $this->fetchAll(
            "SELECT p.project_id, p.username, p.title, p.short_description, p.description,
                    p.budget, p.billing_cycle, p.posted_at, p.expiry_date, p.created_at, p.updated_at,
                    u.full_name AS poster_name
             FROM projects p
             LEFT JOIN users u ON u.username = p.username
             $where
             ORDER BY p.posted_at DESC
             LIMIT ? OFFSET ?",
            [$username, $perPage, $offset]
        );

        return ['total' => $total, 'projects' => array_map(fn($r) => array_merge($r, [
            'id' => $r['project_id'], 'files' => [],
        ]), $projects)];
    }

    public function listForConsultant(string $query, int $page, int $perPage): array
    {
        $where  = '(p.expiry_date IS NULL OR p.expiry_date >= CURDATE())';
        $params = [];

        if ($query !== '') {
            $where   .= ' AND (p.title LIKE ? OR p.short_description LIKE ? OR p.description LIKE ?)';
            $like     = '%' . $query . '%';
            $params   = [$like, $like, $like];
        }

        $total = (int)($this->fetchOne(
            "SELECT COUNT(*) AS cnt FROM projects p WHERE $where",
            $params
        )['cnt'] ?? 0);

        if ($total === 0) return ['total' => 0, 'projects' => []];

        $offset   = ($page - 1) * $perPage;
        $projects = $this->fetchAll(
            "SELECT p.project_id, p.username, p.title, p.short_description, p.description,
                    p.budget, p.billing_cycle, p.links, p.posted_at, p.expiry_date,
                    p.created_at, p.updated_at, u.full_name AS poster_name
             FROM projects p
             LEFT JOIN users u ON u.username = p.username
             WHERE $where
             ORDER BY p.posted_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        return ['total' => $total, 'projects' => array_map(fn($r) => array_merge($r, [
            'id' => $r['project_id'], 'files' => [],
        ]), $projects)];
    }

    public function getApplications(string $projectId): array
    {
        return $this->fetchAll(
            'SELECT a.username, a.user_msg, a.portfolio_link, NULL AS applied_at,
                    c.name AS full_name, c.profile_img, c.State AS state, c.identity,
                    c.area_of_expertise, c.bio, c.experience, c.phone, c.email,
                    c.hourly_rate, c.verification, c.created_at
             FROM project_applications a
             LEFT JOIN consultants c ON c.username = a.username
             WHERE a.project_id = ?
             ORDER BY c.name ASC',
            [$projectId]
        );
    }

    public function apply(string $projectId, string $consultantUsername,
                          string $userMsg, string $portfolioLink): bool
    {
        $existing = $this->fetchOne(
            'SELECT COUNT(*) AS cnt FROM project_applications WHERE project_id=? AND username=?',
            [$projectId, $consultantUsername]
        );
        if ((int)($existing['cnt'] ?? 0) > 0) return false;

        $this->execute(
            'INSERT INTO project_applications (project_id, username, user_msg, portfolio_link)
             VALUES (?, ?, ?, ?)',
            [$projectId, $consultantUsername, $userMsg, $portfolioLink]
        );
        return true;
    }

    public function findWithUploader(string $projectId): ?array
    {
        return $this->fetchOne(
            'SELECT p.title, u.email AS uploader_email
             FROM projects p
             INNER JOIN users u ON u.username = p.username
             WHERE p.project_id = ? LIMIT 1',
            [$projectId]
        );
    }
}
