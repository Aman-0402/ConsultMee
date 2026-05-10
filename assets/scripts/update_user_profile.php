<?php
session_start();
header('Content-Type: application/json');

// ✅ DB connection
$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme'); 
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// ✅ Ensure user is logged in
$username = $_SESSION['user']['username'] ?? null;
if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// ==========================================================
// 🔥 FETCH OLD IMAGE
// ==========================================================
$oldImage = null;

$getStmt = $conn->prepare("SELECT profile_img FROM users WHERE username = ?");
$getStmt->bind_param("s", $username);
$getStmt->execute();
$getStmt->bind_result($oldImage);
$getStmt->fetch();
$getStmt->close();

// ==========================================================
// INPUT DATA
// ==========================================================
$full_name = $_POST['full_name'] ?? '';
$identity  = $_POST['identity'] ?? '';
$phone     = $_POST['phone'] ?? '';
$state     = $_POST['state'] ?? '';
$interest1 = $_POST['interest1'] ?? '';
$interest2 = $_POST['interest2'] ?? '';
$interest3 = $_POST['interest3'] ?? '';

$profileImage = null;

// ==========================================================
// IMAGE COMPRESSION FUNCTION
// ==========================================================
function compressImageTo10KB($sourcePath, $destinationPath, $maxSizeKB = 10) {
    $info = getimagesize($sourcePath);
    if (!$info) return false;

    $mime = $info['mime'];

    if ($mime === 'image/jpeg') {
        $image = imagecreatefromjpeg($sourcePath);
    } elseif ($mime === 'image/png') {
        $image = imagecreatefrompng($sourcePath);
    } elseif ($mime === 'image/webp') {
        $image = imagecreatefromwebp($sourcePath);
    } else {
        return false;
    }

    if (!$image) return false;

    $width  = imagesx($image);
    $height = imagesy($image);

    $maxDim = 600;
    if ($width > $maxDim || $height > $maxDim) {
        $ratio = min($maxDim / $width, $maxDim / $height);
        $newW = (int)($width * $ratio);
        $newH = (int)($height * $ratio);

        $resized = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);

        imagedestroy($image);
        $image = $resized;
    }

    $targetBytes = $maxSizeKB * 1024;
    $quality = 80;

    do {
        ob_start();
        imagejpeg($image, null, $quality);
        $data = ob_get_clean();

        if (strlen($data) <= $targetBytes) {
            file_put_contents($destinationPath, $data);
            imagedestroy($image);
            return true;
        }

        $quality -= 5;

    } while ($quality >= 10);

    file_put_contents($destinationPath, $data);
    imagedestroy($image);

    return true;
}

// ==========================================================
// HANDLE IMAGE UPLOAD
// ==========================================================
if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])) {

    $uploadDir = '../../uploads/users/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $tmpPath = $_FILES['profile_image']['tmp_name'];

    $fileName = $username . "_" . time() . ".jpg";
    $filePath = $uploadDir . $fileName;

    if (compressImageTo10KB($tmpPath, $filePath, 10)) {

        // ==========================================================
        // 🔥 DELETE OLD IMAGE
        // ==========================================================
        if (!empty($oldImage) && $oldImage !== 'default.jpg') {

            $oldImage = basename($oldImage); // security
            $oldPath = $uploadDir . $oldImage;

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $profileImage = $fileName;

    } else {
        echo json_encode(['success' => false, 'message' => 'Image compression failed']);
        exit;
    }
}

// ==========================================================
// UPDATE QUERY
// ==========================================================
if ($profileImage) {

    $sql = "UPDATE users 
            SET full_name = ?, phone = ?, state = ?, interest1 = ?, interest2 = ?, interest3 = ?, profile_img = ?, identity = ?
            WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssssss",
        $full_name,
        $phone,
        $state,
        $interest1,
        $interest2,
        $interest3,
        $profileImage,
        $identity,
        $username
    );

} else {

    $sql = "UPDATE users 
            SET full_name = ?, phone = ?, state = ?, interest1 = ?, interest2 = ?, interest3 = ?, identity = ?
            WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssssss",
        $full_name,
        $phone,
        $state,
        $interest1,
        $interest2,
        $interest3,
        $identity,
        $username
    );
}

// ==========================================================
// EXECUTION
// ==========================================================
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User profile updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
exit;
?>