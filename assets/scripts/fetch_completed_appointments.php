<?php
session_start();
header('Content-Type: application/json');

// Optional: Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if consultant is logged in
if (!isset($_SESSION['consultant']['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$consultant_username = $_SESSION['consultant']['username'];

// Database connection
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'details' => $conn->connect_error
    ]);
    exit;
}

// Fetch completed or cancelled appointments (use exact ENUM values)
$sql = "SELECT appointment_id, users_name, appointment_date, appointment_time, mode_of_appointment, status, meeting_link, user_request, consultant_message 
        FROM appointments 
        WHERE consultant_username = ? 
        AND status IN ('completed', 'cancelled')
        ORDER BY appointment_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Query preparation failed', 'details' => $conn->error]);
    exit;
}

$stmt->bind_param("s", $consultant_username);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Query execution failed', 'details' => $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$appointments = [];

while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

echo json_encode($appointments, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>
