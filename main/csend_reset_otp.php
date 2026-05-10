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
if (empty($email)) {
    echo json_encode(['error' => 'Email required']);
    exit;
}

// Check consultant existence
$stmt = $conn->prepare("SELECT id FROM consultants WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Email not found in consultant database']);
    exit;
}

// Generate OTP
$otp = rand(100000, 999999);
$expires_at = time() + (10 * 60);
$otp_hash = password_hash($otp, PASSWORD_DEFAULT);

// Insert/update reset entry
$check = $conn->prepare("SELECT email FROM password_resets WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $update = $conn->prepare("UPDATE password_resets SET otp_hash=?, expires_at=? WHERE email=?");
    $update->bind_param("sis", $otp_hash, $expires_at, $email);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO password_resets (email, otp_hash, expires_at) VALUES (?, ?, ?)");
    $insert->bind_param("ssi", $email, $otp_hash, $expires_at);
    $insert->execute();
}

// Send OTP mail
$subject = "ConsultME Consultant Password Reset OTP";
$message = "Your OTP for password reset is: $otp\n\nIt will expire in 10 minutes.";
$headers = "From: no-reply@consultmee.in";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['success' => 'OTP sent successfully to your registered email.']);
} else {
    echo json_encode(['error' => 'Failed to send OTP email.']);
}
?>
