<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');

if (empty($email) || empty($otp) || empty($new_password)) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// Fetch reset record
$stmt = $conn->prepare("SELECT otp_hash, expires_at FROM password_resets WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'No OTP request found.']);
    exit;
}

$row = $result->fetch_assoc();

if (time() > $row['expires_at']) {
    echo json_encode(['error' => 'OTP expired. Please request again.']);
    exit;
}

if (!password_verify($otp, $row['otp_hash'])) {
    echo json_encode(['error' => 'Invalid OTP.']);
    exit;
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE consultants SET password=? WHERE email=?");
$update->bind_param("ss", $hashed_password, $email);
$update->execute();

// Remove used OTP
$delete = $conn->prepare("DELETE FROM password_resets WHERE email=?");
$delete->bind_param("s", $email);
$delete->execute();

echo json_encode(['success' => 'Password reset successfully.']);
?>
