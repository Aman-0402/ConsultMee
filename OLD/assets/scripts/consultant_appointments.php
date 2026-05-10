<?php
// consultant_appointments.php
session_start();
header('Content-Type: application/json');

// Enable error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if consultant is logged in
if (!isset($_SESSION['consultant']['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Consultant not logged in']);
    exit;
}

$consultant_username = $_SESSION['consultant']['username'];

// Database connection
$host = 'localhost';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';   // change if password exists
$dbname = 'yibnyzre_cme';

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'details' => $conn->connect_error
    ]);
    exit;
}

// Prepare SQL to fetch pending appointments with COLLATE fix
$sql = "SELECT 
            a.appointment_id,
            a.users_name,
            a.appointment_date,
            a.appointment_time,
            a.mode_of_appointment,
            a.status,
            a.meeting_link,
            a.user_request,
            a.consultant_message,
            u.profile_img
        FROM 
            appointments a
        JOIN 
            users u ON a.user_username COLLATE utf8mb4_general_ci = u.username COLLATE utf8mb4_general_ci
        WHERE 
            a.consultant_username COLLATE utf8mb4_general_ci = ? 
            AND a.status = 'pending'
        ORDER BY 
            a.appointment_date ASC";

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

// Send clean JSON response
echo json_encode($appointments, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>