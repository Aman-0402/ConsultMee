<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Extract user's interests
$interests = [
    $_SESSION['user']['interest1'],
    $_SESSION['user']['interest2'],
    $_SESSION['user']['interest3']
];

// Database connection
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Prepare SQL to match area_of_expertise with any interest
$placeholders = implode(',', array_fill(0, count($interests), '?'));
$sql = "SELECT username, name, area_of_expertise, bio, experience, phone, email FROM consultants WHERE area_of_expertise IN ($placeholders)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($interests)), ...$interests);
$stmt->execute();
$result = $stmt->get_result();

$consultants = [];
while ($row = $result->fetch_assoc()) {
    $consultants[] = $row;
}

echo json_encode($consultants);
$conn->close();
?>