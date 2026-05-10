<?php
// assets/scripts/fetch_projects_consultant.php
header('Content-Type: application/json; charset=utf-8');

// Debug toggle (1 during dev, 0 in production)
$DEBUG = 0;
if ($DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

session_start();

function json_out($arr) {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Require consultant login (as set in clogin.php)
if (empty($_SESSION['consultant']) || empty($_SESSION['consultant']['username'])) {
    json_out(['success' => false, 'message' => 'not_authenticated']);
}

$consultant = $_SESSION['consultant']; // username not used for filtering

// ---------------- DB CONNECTION ----------------
$dbHost = 'localhost';
$dbName = 'yibnyzre_cme';
$dbUser = 'yibnyzre_cme_admin';
$dbPass = 'As792002@';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    if ($DEBUG) {
        json_out([
            'success' => false,
            'message' => 'db_connect_error',
            'error'   => $conn->connect_error
        ]);
    }
    json_out(['success' => false, 'message' => 'db_connect_error']);
}
$conn->set_charset('utf8mb4');

// ---------------- INPUTS ----------------
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = min(50, max(5, (int)($_GET['per_page'] ?? 10)));
$offset   = ($page - 1) * $per_page;
$q        = trim($_GET['q'] ?? ''); // optional search text

// ---------------- WHERE: ONLY ACTIVE ----------------
// Active = expiry_date IS NULL OR expiry_date >= today
$where  = [];
$params = [];
$types  = '';

$where[] = "(p.expiry_date IS NULL OR p.expiry_date >= CURDATE())";

if ($q !== '') {
    $where[] = "(p.title LIKE ? OR p.short_description LIKE ? OR p.description LIKE ?)";
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types   .= 'sss';
}

$whereSql = 'WHERE ' . implode(' AND ', $where);

try {
    // ------------- COUNT TOTAL ACTIVE -------------
    $countSql = "SELECT COUNT(*) AS cnt FROM projects p $whereSql";
    $countStmt = $conn->prepare($countSql);
    if (!$countStmt) {
        throw new Exception("Count prepare failed: " . $conn->error);
    }

    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }

    $countStmt->execute();
    $cres = $countStmt->get_result();
    $crow = $cres->fetch_assoc();
    $total = (int)($crow['cnt'] ?? 0);
    $countStmt->close();

    if ($total === 0) {
        json_out([
            'success'  => true,
            'page'     => $page,
            'per_page' => $per_page,
            'total'    => 0,
            'projects' => []
        ]);
    }

    // ------------- FETCH ACTIVE PROJECTS -------------
    $sql = "SELECT 
                p.project_id,
                p.username,
                p.title,
                p.short_description,
                p.description,
                p.budget,
                p.billing_cycle,
                p.links,
                p.posted_at,
                p.expiry_date,
                p.created_at,
                p.updated_at,
                u.full_name AS poster_name
            FROM projects p
            LEFT JOIN users u ON u.username = p.username
            $whereSql
            ORDER BY p.posted_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $bindTypes = $types . 'ii';
    $bindVals  = $params;
    $bindVals[] = $per_page;
    $bindVals[] = $offset;

    $stmt->bind_param($bindTypes, ...$bindVals);
    $stmt->execute();
    $res = $stmt->get_result();

    $projects = [];
    while ($r = $res->fetch_assoc()) {
        $pid = $r['project_id'];

        $projects[] = [
            'project_id'        => $pid,
            'id'                => $pid,
            'username'          => $r['username'],
            'poster_name'       => $r['poster_name'] ?? $r['username'],
            'title'             => $r['title'],
            'short_description' => $r['short_description'],
            'description'       => $r['description'],
            'budget'            => $r['budget'],
            'billing_cycle'     => $r['billing_cycle'],
            'links'             => $r['links'],
            'posted_at'         => $r['posted_at'],
            'expiry_date'       => $r['expiry_date'],
            'created_at'        => $r['created_at'],
            'updated_at'        => $r['updated_at'],
            'files'             => []
        ];
    }
    $stmt->close();

    json_out([
        'success'  => true,
        'page'     => $page,
        'per_page' => $per_page,
        'total'    => $total,
        'projects' => $projects
    ]);

} catch (Exception $e) {
    if ($DEBUG) {
        json_out([
            'success' => false,
            'message' => 'exception',
            'error'   => $e->getMessage()
        ]);
    }
    json_out(['success' => false, 'message' => 'server_error']);
}