<?php
session_start();
header('Content-Type: application/json');

// 1. Check if consultant is logged in
$consultantUsername = $_SESSION['consultant']['username'] ?? null;
if (!$consultantUsername) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// 2. DB connection
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

/**
 * 3. Fetch profile + rating summary
 *
 * Tables:
 * - consultants
 * - consultant_ratings
 *
 * consultant_ratings columns:
 * - id (PK)
 * - appointment_id
 * - consultant_username
 * - user_username
 * - rating (INT, nullable)
 * - feedback
 * - created_at
 *
 * AVG(r.rating) will automatically ignore NULL ratings.
 */
$sql = "
    SELECT 
        c.username,
        c.name,
        c.email,
        c.phone,
        c.area_of_expertise,
        c.bio,
        c.experience,
        c.State,
        c.hourly_rate,
        c.identity,
        c.profile_img,
        COALESCE(ROUND(AVG(r.rating), 1), 0) AS avg_rating,
        COUNT(r.id) AS total_reviews
    FROM consultants c
    LEFT JOIN consultant_ratings r
        ON r.consultant_username = c.username
    WHERE c.username = ?
    GROUP BY 
        c.username,
        c.name,
        c.email,
        c.phone,
        c.area_of_expertise,
        c.bio,
        c.experience,
        c.State,
        c.hourly_rate,
        c.identity,
        c.profile_img
    LIMIT 1
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed']);
    $conn->close();
    exit;
}

$stmt->bind_param("s", $consultantUsername);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(['error' => 'Consultant profile not found']);
    $stmt->close();
    $conn->close();
    exit;
}

// 4. Return JSON in the shape your JS expects
echo json_encode([
    'username'        => $row['username'],
    'name'            => $row['name'],
    'email'           => $row['email'],
    'phone'           => $row['phone'],
    'area_of_expertise' => $row['area_of_expertise'],
    'bio'             => $row['bio'],
    'experience'      => $row['experience'],
    'state'           => $row['State'],
    'hourly_rate'     => $row['hourly_rate'],
    'identity'        => $row['identity'],
    'profile_img'     => $row['profile_img'],
    'avg_rating'      => (float)$row['avg_rating'],
    'total_reviews'   => (int)$row['total_reviews']
]);

$stmt->close();
$conn->close();
?>