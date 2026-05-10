<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Extract and escape interests from session
$interest1 = $_SESSION['user']['interest1'];
$interest2 = $_SESSION['user']['interest2'];
$interest3 = $_SESSION['user']['interest3'];

// Database connection
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// SQL with LEFT JOIN to get average rating and total reviews
$sql = "
    SELECT 
        c.username, 
        c.name, 
        c.area_of_expertise, 
        c.bio, 
        c.experience, 
        c.state, 
        c.hourly_rate,
        c.identity,
        c.profile_img,
        ROUND(AVG(r.rating), 1) AS avg_rating,
        COUNT(r.id) AS total_reviews
    FROM consultants c
    LEFT JOIN consultant_ratings r ON c.username = r.consultant_username
    WHERE c.area_of_expertise = ? OR c.area_of_expertise = ? OR c.area_of_expertise = ?
    GROUP BY c.username
";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $interest1, $interest2, $interest3);
$stmt->execute();
$result = $stmt->get_result();

$consultants = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consultants[] = $row;
    }
    echo json_encode($consultants);
} else {
    echo json_encode([]);
}

$conn->close();
?>
