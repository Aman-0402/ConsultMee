<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

// Check session
if (!isset($_SESSION['consultant']['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$consultantId = $_SESSION['consultant']['username'];

// Query with COLLATE fix
$query = "SELECT 
    a.*, 
    u.profile_img
FROM 
    appointments a
JOIN 
    users u 
ON 
    a.user_username COLLATE utf8mb4_general_ci = u.username COLLATE utf8mb4_general_ci
WHERE 
    a.consultant_username COLLATE utf8mb4_general_ci = ? 
    AND a.status = 'confirmed'";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("s", $consultantId);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$appointments = [];

while ($row = $result->fetch_assoc()) {
    $row['profile_img'] = $row['profile_img'] ?? 'default.png';
    // Use users_name only if user_full_name does not exist
    $row['users_name'] = $row['user_full_name'] ?? $row['users_name'];
    $appointments[] = $row;
}

echo json_encode($appointments, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>