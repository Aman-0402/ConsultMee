<?php
session_start();
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

$appointment_id = $_POST['appointment_id'];
$consultant_username = $_POST['consultant_username'];
$user_username = $_POST['user_username'];
$rating = intval($_POST['rating']);
$feedback = $_POST['feedback'];

// Prevent duplicates using INSERT IGNORE or REPLACE
$stmt = $conn->prepare("INSERT IGNORE INTO consultant_ratings (appointment_id, consultant_username, user_username, rating, feedback) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $appointment_id, $consultant_username, $user_username, $rating, $feedback);
$success = $stmt->execute();

if ($success) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>
