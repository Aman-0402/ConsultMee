<?php
session_start();
header('Content-Type: application/json');

$mysqli = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');
if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit;
}

$username = $_SESSION['consultant']['username'] ?? null;
if (!$username) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ==========================
// FETCH OLD IMAGE
// ==========================
$oldImage = null;

$getStmt = $mysqli->prepare("SELECT profile_img FROM consultants WHERE username = ?");
$getStmt->bind_param("s", $username);
$getStmt->execute();
$getStmt->bind_result($oldImage);
$getStmt->fetch();
$getStmt->close();

// ==========================
// INPUTS
// ==========================
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$area_of_expertise = $_POST['area_of_expertise'] ?? '';
$bio = $_POST['bio'] ?? '';
$experience = $_POST['experience'] ?? '';
$rate = $_POST['rate'] ?? '';
$state = $_POST['state'] ?? '';
$identity = $_POST['identity'] ?? '';

$profileImage = null;

// ==========================
// IMAGE COMPRESS FUNCTION
// ==========================
function compressToTargetSize($sourcePath, $destPath, $targetKB = 10) {
    $targetBytes = $targetKB * 1024;

    $info = @getimagesize($sourcePath);
    if (!$info) return false;

    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $img = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $img = imagecreatefrompng($sourcePath);
            break;
        case 'image/webp':
            $img = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    if (!$img) return false;

    $width = imagesx($img);
    $height = imagesy($img);

    $newImg = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($newImg, 255, 255, 255);
    imagefill($newImg, 0, 0, $white);

    imagecopy($newImg, $img, 0, 0, 0, 0, $width, $height);
    imagedestroy($img);

    $quality = 70;

    do {
        ob_start();
        imagejpeg($newImg, null, $quality);
        $imageData = ob_get_clean();

        if (strlen($imageData) <= $targetBytes || $quality <= 10) {
            file_put_contents($destPath, $imageData);
            break;
        }

        $quality -= 5;

    } while ($quality > 5);

    imagedestroy($newImg);

    return file_exists($destPath);
}

// ==========================
// FILE UPLOAD
// ==========================
if (!empty($_FILES['profile_image']['name'])) {

    $uploadDir = __DIR__ . '/../../uploads/consultants/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            echo json_encode(['error' => 'Failed to create upload directory']);
            exit;
        }
    }

    if (!is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
        echo json_encode(['error' => 'No uploaded file found']);
        exit;
    }

    $tmpPath = $_FILES['profile_image']['tmp_name'];
    $imgInfo = @getimagesize($tmpPath);

    if (!$imgInfo) {
        echo json_encode(['error' => 'Invalid image file']);
        exit;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($imgInfo['mime'], $allowedTypes)) {
        echo json_encode(['error' => 'Only JPG, PNG, WEBP allowed']);
        exit;
    }

    $fileName = $username . "_" . time() . ".jpg";
    $filePath = $uploadDir . $fileName;

    if (!compressToTargetSize($tmpPath, $filePath, 10)) {
        echo json_encode(['error' => 'Image compression failed']);
        exit;
    }

    // ==========================
    // DELETE OLD IMAGE
    // ==========================
    if (!empty($oldImage) && $oldImage !== 'default.jpg') {
        $oldImage = basename($oldImage); // security

        $oldImagePath = $uploadDir . $oldImage;

        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    $profileImage = $fileName;
}

// ==========================
// UPDATE QUERY
// ==========================
if ($profileImage) {
    $sql = "UPDATE consultants
            SET name = ?, phone = ?, area_of_expertise = ?, bio = ?, experience = ?, State = ?, hourly_rate = ?, profile_img = ?, identity = ?
            WHERE username = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        "ssssssisss",
        $name,
        $phone,
        $area_of_expertise,
        $bio,
        $experience,
        $state,
        $rate,
        $profileImage,
        $identity,
        $username
    );

} else {
    $sql = "UPDATE consultants
            SET name = ?, phone = ?, area_of_expertise = ?, bio = ?, experience = ?, State = ?, hourly_rate = ?, identity = ?
            WHERE username = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param(
        "ssssssiss",
        $name,
        $phone,
        $area_of_expertise,
        $bio,
        $experience,
        $state,
        $rate,
        $identity,
        $username
    );
}

// ==========================
// EXECUTION
// ==========================
if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['error' => 'Update failed: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
exit;
?>