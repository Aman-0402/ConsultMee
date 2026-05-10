<?php
header('Content-Type: application/json');

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

if (!isset($_GET['category']) || empty($_GET['category'])) {
    echo json_encode(["error" => "Category not specified"]);
    exit;
}

$category = $_GET['category'];

$stmt = $conn->prepare("SELECT name, TRIM(username) AS username, area_of_expertise, bio, experience, state, hourly_rate, profile_img FROM consultants WHERE area_of_expertise = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$consultants = [];
while ($row = $result->fetch_assoc()) {
    $consultants[] = $row;
}

echo json_encode($consultants);
$conn->close();
?>
