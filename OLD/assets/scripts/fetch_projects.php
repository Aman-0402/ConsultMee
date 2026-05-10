<?php
// assets/scripts/fetch_projects.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// --- auth
if (empty($_SESSION['user']['username'])) {
    echo json_encode(['success' => false, 'message' => 'not_authenticated']);
    exit;
}
$viewerUsername = $_SESSION['user']['username'];

// --- DB settings
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}
$conn->set_charset('utf8mb4');

// --- inputs & paging
$filter = (isset($_GET['filter']) && $_GET['filter'] === 'history') ? 'history' : 'active';
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = min(50, max(5, (int)($_GET['per_page'] ?? 10)));
$offset = ($page - 1) * $per_page;

try {
    // Build WHERE clause based on filter (no status column in schema)
    if ($filter === 'history') {
        // history = expired projects (expiry_date exists and < today)
        $where = "WHERE p.expiry_date IS NOT NULL AND p.expiry_date < CURDATE() AND p.username = ?";
    } else {
        // active = not expired (expiry_date NULL or >= today)
        $where = "WHERE (p.expiry_date IS NULL OR p.expiry_date >= CURDATE()) AND p.username = ?";
    }

    // Count total
    $countSql = "SELECT COUNT(*) AS cnt FROM projects p $where";
    $countStmt = $conn->prepare($countSql);
    if ($countStmt === false) throw new Exception("Count prepare failed: " . $conn->error);
    $countStmt->bind_param('s', $viewerUsername);
    if (!$countStmt->execute()) {
        $err = $countStmt->error;
        $countStmt->close();
        throw new Exception("Count execute failed: " . $err);
    }
    $cntRes = $countStmt->get_result();
    if ($cntRes === false) {
        $err = $countStmt->error;
        $countStmt->close();
        throw new Exception("Count get_result failed: " . $err);
    }
    $cntRow = $cntRes->fetch_assoc();
    $total = intval($cntRow['cnt'] ?? 0);
    $countStmt->close();

    // If none, return empty list
    if ($total === 0) {
        echo json_encode([
            'success' => true,
            'filter' => $filter,
            'page' => $page,
            'per_page' => $per_page,
            'total' => $total,
            'projects' => []
        ]);
        exit;
    }

    // Fetch projects (select columns that exist in schema)
    $sql = "SELECT p.project_id, p.username, p.title, p.short_description, p.description, p.budget, p.billing_cycle,
                   p.posted_at, p.expiry_date, p.created_at, p.updated_at,
                   u.full_name AS poster_name
            FROM projects p
            LEFT JOIN users u ON u.username = p.username
            $where
            ORDER BY p.posted_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) throw new Exception("Prepare failed: " . $conn->error);

    // bind: username (s), per_page (i), offset (i)
    if (!$stmt->bind_param('sii', $viewerUsername, $per_page, $offset)) {
        $err = $stmt->error;
        $stmt->close();
        throw new Exception("bind_param failed: " . $err);
    }

    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new Exception("Execute failed: " . $err);
    }

    $res = $stmt->get_result();
    if ($res === false) {
        $err = $stmt->error;
        $stmt->close();
        throw new Exception("get_result failed: " . $err);
    }

    $projects = [];
    while ($row = $res->fetch_assoc()) {
        $pid = $row['project_id'];
        $projects[$pid] = [
            // include both project_id and id for front-end compatibility
            'project_id' => $pid,
            'id' => $pid,
            'username' => $row['username'],
            'poster_name' => $row['poster_name'] ?? $row['username'],
            'title' => $row['title'],
            'short_description' => $row['short_description'],
            'description' => $row['description'],
            'budget' => $row['budget'],
            'billing_cycle' => $row['billing_cycle'],
            'posted_at' => $row['posted_at'],
            'expiry_date' => $row['expiry_date'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'files' => [] // kept for compatibility; will be empty since files are only stored on server
        ];
    }
    $stmt->close();

    $outProjects = array_values($projects);

    echo json_encode([
        'success' => true,
        'filter' => $filter,
        'page' => $page,
        'per_page' => $per_page,
        'total' => $total,
        'projects' => $outProjects
    ]);
    exit;

} catch (Exception $e) {
    // In production hide $e->getMessage(); here it's helpful for debugging
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}