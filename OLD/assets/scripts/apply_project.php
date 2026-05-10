<?php
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json; charset=utf-8');
session_start();

/* ---------- AUTH ---------- */
if (empty($_SESSION['consultant']['username'])) {
    echo json_encode(['success' => false, 'error' => 'not_authenticated']);
    exit;
}

$consultantUsername = $_SESSION['consultant']['username'];

/* ---------- DB ---------- */
$conn = new mysqli(
    'localhost',
    'yibnyzre_cme_admin',
    'As792002@',
    'yibnyzre_cme'
);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'db_connect_error']);
    exit;
}
$conn->set_charset('utf8mb4');

/* ---------- INPUT ---------- */
$data = json_decode(file_get_contents('php://input'), true);

$project_id     = substr(trim($data['project_id'] ?? ''), 0, 20);
$user_msg       = substr(trim($data['user_msg'] ?? ''), 0, 150);
$portfolio_link = substr(trim($data['portfolio_link'] ?? ''), 0, 100);

if (!$project_id || !$user_msg) {
    echo json_encode(['success' => false, 'error' => 'project_id and user_msg required']);
    exit;
}

/* ---------- DUPLICATE CHECK ---------- */
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM project_applications WHERE project_id=? AND username=?"
);
$stmt->bind_param('ss', $project_id, $consultantUsername);
$stmt->execute();
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();

if ($cnt > 0) {
    echo json_encode(['success' => false, 'error' => 'Already applied']);
    exit;
}

/* ---------- INSERT ---------- */
$stmt = $conn->prepare(
    "INSERT INTO project_applications (project_id, username, user_msg, portfolio_link)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param('ssss', $project_id, $consultantUsername, $user_msg, $portfolio_link);
$stmt->execute();
$stmt->close();

/* ---------- PROJECT + USER EMAIL ---------- */
$stmt = $conn->prepare("
    SELECT p.title, u.email AS uploader_email
    FROM projects p
    INNER JOIN users u ON u.username = p.username
    WHERE p.project_id = ?
    LIMIT 1
");
$stmt->bind_param('s', $project_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    echo json_encode(['success' => false, 'error' => 'Project not found']);
    exit;
}

$projectTitle  = $project['title'];
$uploaderEmail = $project['uploader_email'];

/* ---------- CONSULTANT EMAIL ---------- */
$stmt = $conn->prepare(
    "SELECT name, email FROM consultants WHERE username=? LIMIT 1"
);
$stmt->bind_param('s', $consultantUsername);
$stmt->execute();
$consultant = $stmt->get_result()->fetch_assoc();
$stmt->close();

$consultantName  = $consultant['name'];
$consultantEmail = $consultant['email'];

/* ---------- EMAIL HEADERS ---------- */
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: ConsultME <no-reply@consultmee.in>\r\n";

/* ---------- CONSULTANT EMAIL ---------- */
$consultantBody = "
<div style='font-family:Segoe UI,Arial;background:#f4f6f8;padding:30px'>
  <div style='max-width:600px;margin:auto;background:#fff;border-radius:14px;padding:30px'>
    <h2 style='color:#2563eb'>Application Submitted ✅</h2>
    <p>Hi <strong>{$consultantName}</strong>,</p>
    <p>You have successfully applied for:</p>
    <div style='background:#f9fafb;padding:14px;border-radius:8px'>
      <strong>{$projectTitle}</strong>
    </div>
    <p style='margin-top:20px'>
      Login to your dashboard to track updates.
    </p>
    <p style='color:#6b7280;font-size:13px'>– Team ConsultME</p>
  </div>
</div>
";

/* ---------- USER EMAIL ---------- */
$uploaderBody = "
<div style='font-family:Segoe UI,Arial;background:#f4f6f8;padding:30px'>
  <div style='max-width:600px;margin:auto;background:#fff;border-radius:14px;padding:30px'>
    <h2 style='color:#059669'>New Project Application 📩</h2>
    <p>
      <strong>{$consultantName}</strong> (@{$consultantUsername})
      has applied for your project:
    </p>
    <div style='background:#ecfeff;padding:14px;border-radius:8px'>
      <strong>{$projectTitle}</strong>
    </div>
    <p style='margin-top:15px'><strong>Message:</strong></p>
    <div style='background:#f9fafb;padding:14px;border-radius:8px'>
      {$user_msg}
    </div>
    <p style='margin-top:20px;color:#6b7280;font-size:13px'>
      Login to ConsultME to review the application.
    </p>
  </div>
</div>
";

/* ---------- SEND MAIL ---------- */
@mail($consultantEmail, 'Application Submitted – ConsultME', $consultantBody, $headers);
@mail($uploaderEmail, 'New Project Application – ConsultME', $uploaderBody, $headers);

/* ---------- RESPONSE ---------- */
echo json_encode([
    'success' => true,
    'message' => 'Application submitted successfully'
]);