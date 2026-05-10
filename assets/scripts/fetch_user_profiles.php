<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user']['username'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// Database connection
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

// Check DB connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$user_id = $_SESSION['user']['username'];

// Fetch user details
$sql = "SELECT full_name, username, phone, email, interest1, interest2, interest3, state, identity, profile_img 
        FROM users 
        WHERE username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Check if profile image is available
    $profileImg = (!empty($row['profile_img'])) ? './uploads/users/' . $row['profile_img'] : './uploads/users/default.png';

    echo json_encode([
        'full_name' => $row['full_name'],
        'username' => $row['username'],
        'phone' => $row['phone'],
        'email' => $row['email'],
        'interest1' => $row['interest1'],
        'interest2' => $row['interest2'],
        'interest3' => $row['interest3'],
        'state' => $row['state'],
        'identity' => $row['identity'],
        'profile_img' => $profileImg
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
