<?php
session_start();
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

if (!isset($_SESSION['user']['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$username = $_SESSION['user']['username'];

$sql = "SELECT 
  a.*, 
  r.rating AS user_rating, 
  r.feedback,
  CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END AS rating_given
FROM appointments a
LEFT JOIN consultant_ratings r 
  ON a.appointment_id = r.appointment_id AND a.user_username = r.user_username
WHERE a.user_username = ?
ORDER BY a.appointment_date DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];

while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

echo json_encode($appointments);

$conn->close();
?>
