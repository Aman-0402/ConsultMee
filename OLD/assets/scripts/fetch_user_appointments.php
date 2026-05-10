<?php
session_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user']['username'])) {
  echo json_encode(['error' => 'User not logged in']);
  exit;
}

$username = $_SESSION['user']['username'];

$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');
if ($conn->connect_error) {
  echo json_encode(['error' => 'DB connection error']);
  exit;
}

$sql = "SELECT appointment_id, consultant_name, appointment_date, appointment_time, status, user_request, consultant_message, meeting_link
        FROM appointments 
        WHERE user_username = ? AND status !='cancelled'
        ORDER BY appointment_date DESC, appointment_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];

while ($row = $result->fetch_assoc()) {
  $appointments[] = $row;
}

echo json_encode($appointments);
?>