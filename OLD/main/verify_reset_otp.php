<?php
// ✅ Display errors temporarily for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// ✅ Database connection
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// ✅ Sanitize inputs
$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');

if (empty($email) || empty($otp) || empty($new_password)) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// ✅ Check if reset request exists
$stmt = $conn->prepare("SELECT otp_hash, expires_at FROM password_resets WHERE email=?");
if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'No OTP request found for this email.']);
    exit;
}

$row = $result->fetch_assoc();

// ✅ Check if OTP expired
if (time() > $row['expires_at']) {
    echo json_encode(['error' => 'OTP expired. Please request a new one.']);
    exit;
}

// ✅ Verify OTP
if (!password_verify($otp, $row['otp_hash'])) {
    echo json_encode(['error' => 'Invalid OTP entered.']);
    exit;
}

// ✅ Hash new password securely
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// ✅ Update user password
$update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
if (!$update) {
    echo json_encode(['error' => 'Failed to prepare password update query: ' . $conn->error]);
    exit;
}
$update->bind_param("ss", $hashed_password, $email);
if (!$update->execute()) {
    echo json_encode(['error' => 'Password update failed: ' . $update->error]);
    exit;
}

// ✅ Delete OTP record (security cleanup)
$delete = $conn->prepare("DELETE FROM password_resets WHERE email=?");
$delete->bind_param("s", $email);
$delete->execute();

echo json_encode(['success' => 'Password reset successfully. You can now log in.']);
?>
