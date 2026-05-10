<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(!$email || !$password){
    echo json_encode(['error' => 'Email and password required']); exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update password
$stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss", $hashed_password, $email);
if($stmt->execute()){
    // Remove reset record for safety
    $conn->query("DELETE FROM password_resets WHERE email='$email'");
    echo json_encode(['success' => 'Password reset successfully']);
} else {
    echo json_encode(['error' => 'Failed to reset password']);
}
