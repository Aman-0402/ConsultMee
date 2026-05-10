<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?msg=" . urlencode("Please login to view consultant profiles.") . "&type=error");
    exit();
}

// DB connection
$servername = "localhost";
$db_username = "yibnyzre_cme_admin";
$db_password = "As792002@";
$dbname = "yibnyzre_cme";

// Create DB connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$username = $_GET['username'] ?? '';

if (!$username) {
    die("Invalid consultant profile.");
}

/* Fetch Consultant */
$stmt = $conn->prepare("SELECT * FROM consultants WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Consultant not found.");
}

$consultant = $result->fetch_assoc();

/* Fetch Rating Summary */
$ratingStmt = $conn->prepare("
    SELECT 
        ROUND(AVG(rating), 1) AS avg_rating,
        COUNT(*) AS total_reviews
    FROM consultant_ratings
    WHERE consultant_username = ? AND rating IS NOT NULL
");
$ratingStmt->bind_param("s", $username);
$ratingStmt->execute();
$ratingSummary = $ratingStmt->get_result()->fetch_assoc();

$avgRating = $ratingSummary['avg_rating'] ?? 0;
$totalReviews = $ratingSummary['total_reviews'] ?? 0;

/* Fetch Reviews */
$reviewStmt = $conn->prepare("
    SELECT user_username, rating, feedback, created_at
    FROM consultant_ratings
    WHERE consultant_username = ?
    ORDER BY created_at DESC
");
$reviewStmt->bind_param("s", $username);
$reviewStmt->execute();
$reviews = $reviewStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($consultant['name']); ?> | Consultant Profile</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ===============================
       CONSULTME PREMIUM THEME
    =============================== */

    * {
      font-family: "Inter", sans-serif;
    }

    body {
      background: radial-gradient(circle at top left, rgba(13,110,253,0.18), transparent 50%),
                  radial-gradient(circle at bottom right, rgba(20,184,166,0.15), transparent 55%),
                  linear-gradient(180deg, #f7f9ff 0%, #eef2f7 100%);
      min-height: 100vh;
      color: #0f172a;
    }

    /* Background subtle texture */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image: radial-gradient(rgba(15,23,42,0.05) 1px, transparent 1px);
      background-size: 22px 22px;
      opacity: 0.35;
      pointer-events: none;
      z-index: -1;
    }

    .profile-wrapper {
      max-width: 1100px;
      margin: auto;
    }

    /* Main Profile Card */
    .profile-card {
      background: rgba(255,255,255,0.88);
      backdrop-filter: blur(12px);
      border-radius: 22px;
      padding: 40px;
      border: 1px solid rgba(15,23,42,0.08);
      box-shadow: 0 20px 55px rgba(15, 23, 42, 0.12);
      position: relative;
      overflow: hidden;
    }

    /* Decorative glow */
    .profile-card::after {
      content: "";
      position: absolute;
      width: 260px;
      height: 260px;
      background: radial-gradient(circle, rgba(13,110,253,0.18), transparent 70%);
      top: -70px;
      right: -70px;
      z-index: 0;
    }

    .profile-card * {
      position: relative;
      z-index: 2;
    }

    .profile-img {
      width: 175px;
      height: 175px;
      border-radius: 50%;
      object-fit: cover;
      border: 6px solid rgba(13,110,253,0.9);
      box-shadow: 0 14px 35px rgba(15, 23, 42, 0.18);
      transition: 0.3s ease;
    }

    .profile-img:hover {
      transform: scale(1.05);
    }

    .consultant-name {
      font-size: 32px;
      font-weight: 800;
      letter-spacing: -0.6px;
      margin-bottom: 4px;
    }

    .consultant-username {
      color: #64748b;
      font-size: 15px;
      font-weight: 500;
    }

    .badge-pill {
      border-radius: 50px;
      padding: 10px 18px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 0.2px;
      border: 1px solid rgba(15,23,42,0.08);
      box-shadow: 0 10px 25px rgba(15,23,42,0.06);
    }

    .badge-identity {
      background: linear-gradient(135deg, #0d6efd, #2563eb);
      color: #fff;
    }

    .badge-expertise {
      background: linear-gradient(135deg, #0f172a, #334155);
      color: #fff;
    }

    .rating-box {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      border-radius: 14px;
      background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(255,255,255,0.8));
      border: 1px solid rgba(245,158,11,0.25);
      font-weight: 700;
      color: #0f172a;
      box-shadow: 0 10px 25px rgba(15,23,42,0.06);
    }

    .rating-box i {
      color: #f59e0b;
      font-size: 18px;
    }

    .info-grid {
      margin-top: 18px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    .info-card {
      padding: 16px 18px;
      border-radius: 16px;
      background: rgba(255,255,255,0.8);
      border: 1px solid rgba(15,23,42,0.08);
      box-shadow: 0 10px 25px rgba(15,23,42,0.06);
      transition: 0.25s ease;
    }

    .info-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 18px 45px rgba(15,23,42,0.12);
    }

    .info-card small {
      color: #64748b;
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
    }

    .info-card strong {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
    }

    .bio-title {
      font-weight: 800;
      margin-top: 26px;
      font-size: 18px;
    }

    .bio-text {
      color: #475569;
      line-height: 1.85;
      font-size: 15px;
      margin-top: 6px;
    }

    /* Reviews Section */
    .section-title {
      font-weight: 900;
      font-size: 22px;
      margin-top: 35px;
      margin-bottom: 18px;
      letter-spacing: -0.4px;
    }

    .review-card {
      background: rgba(255,255,255,0.92);
      border-radius: 18px;
      padding: 18px 20px;
      border: 1px solid rgba(15,23,42,0.08);
      box-shadow: 0 14px 35px rgba(15,23,42,0.08);
      transition: 0.25s ease;
    }

    .review-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 22px 50px rgba(15,23,42,0.12);
    }

    .review-user {
      font-weight: 800;
      font-size: 15px;
      color: #0f172a;
    }

    .review-date {
      font-size: 13px;
      font-weight: 600;
      color: #64748b;
    }

    .stars {
      color: #f59e0b;
      font-size: 18px;
      letter-spacing: 2px;
    }

    .review-feedback {
      margin-top: 8px;
      font-size: 14.5px;
      color: #475569;
      line-height: 1.75;
    }

    .empty-reviews {
      background: rgba(255,255,255,0.8);
      padding: 18px;
      border-radius: 18px;
      border: 1px dashed rgba(15,23,42,0.15);
      color: #64748b;
      font-weight: 600;
      text-align: center;
    }

    /* Responsive */
    @media(max-width: 992px) {
      .info-grid {
        grid-template-columns: 1fr;
      }
      .profile-card {
        padding: 28px;
      }
      .consultant-name {
        font-size: 26px;
      }
    }
  </style>

</head>

<body>

<div class="container py-5 profile-wrapper">

  <!-- PROFILE CARD -->
  <div class="profile-card mb-4">
    <div class="row g-4 align-items-center">

      <!-- Profile Image -->
      <div class="col-lg-4 text-center">
        <img class="profile-img"
             src="./uploads/consultants/<?php echo !empty($consultant['profile_img']) ? htmlspecialchars($consultant['profile_img']) : 'default.png'; ?>"
             alt="Consultant Profile">
      </div>

      <!-- Profile Info -->
      <div class="col-lg-8">
        <h2 class="consultant-name"><?php echo htmlspecialchars($consultant['name']); ?></h2>
        <p class="consultant-username mb-3">@<?php echo htmlspecialchars($consultant['username']); ?></p>

        <!-- Badges -->
        <div class="d-flex flex-wrap gap-2 mb-3">
          <span class="badge-pill badge-identity">
            <i class="fa-solid fa-user-tie me-2"></i>
            <?php echo htmlspecialchars($consultant['identity'] ?? "Consultant"); ?>
          </span>

          <?php if (!empty($consultant['area_of_expertise'])) { ?>
            <span class="badge-pill badge-expertise">
              <i class="fa-solid fa-briefcase me-2"></i>
              <?php echo htmlspecialchars($consultant['area_of_expertise']); ?>
            </span>
          <?php } ?>
        </div>

        <!-- Rating -->
        <div class="mb-3">
          <span class="rating-box">
            <i class="fa-solid fa-star"></i>
            <?php echo $avgRating; ?>/5
            <span style="font-weight:600;color:#475569;">
              (<?php echo $totalReviews; ?> Reviews)
            </span>
          </span>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
          <div class="info-card">
            <small><i class="fa-solid fa-location-dot me-2"></i>State</small>
            <strong><?php echo htmlspecialchars($consultant['state'] ?? 'N/A'); ?></strong>
          </div>

          <div class="info-card">
            <small><i class="fa-solid fa-indian-rupee-sign me-2"></i>Hourly Rate</small>
            <strong>₹<?php echo htmlspecialchars($consultant['hourly_rate'] ?? 'N/A'); ?>/Hour</strong>
          </div>

          <div class="info-card">
            <small><i class="fa-solid fa-chart-line me-2"></i>Experience</small>
            <strong><?php echo htmlspecialchars($consultant['experience'] ?? 'N/A'); ?></strong>
          </div>
        </div>

        <!-- Bio -->
        <h5 class="bio-title">
          <i class="fa-solid fa-circle-info me-2 text-primary"></i>Bio
        </h5>
        <p class="bio-text">
          <?php echo !empty($consultant['bio']) ? nl2br(htmlspecialchars($consultant['bio'])) : "No bio available."; ?>
        </p>

      </div>

    </div>
  </div>

  <!-- REVIEWS -->
  <h4 class="section-title">
    <i class="fa-solid fa-comments text-primary me-2"></i>Ratings & Reviews
  </h4>

  <?php if ($totalReviews == 0) { ?>
    <div class="empty-reviews">
      <i class="fa-regular fa-face-smile me-2"></i>
      No reviews yet. Be the first to review this consultant!
    </div>
  <?php } else { ?>

    <?php while ($r = $reviews->fetch_assoc()) { ?>
      <div class="review-card mb-3">

        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="review-user">
            <i class="fa-solid fa-user me-2 text-primary"></i>
            <?php echo htmlspecialchars($r['user_username']); ?>
          </span>

          <span class="review-date">
            <i class="fa-regular fa-calendar me-2"></i>
            <?php echo date("d M Y", strtotime($r['created_at'])); ?>
          </span>
        </div>

        <div class="stars mb-2">
          <?php
            $stars = (int)$r['rating'];
            for ($i=1; $i<=5; $i++) {
              echo ($i <= $stars) ? "★" : "☆";
            }
          ?>
          <span class="text-muted" style="font-size:14px; font-weight:600;">
            (<?php echo $stars; ?>/5)
          </span>
        </div>

        <p class="review-feedback mb-0">
          <?php echo !empty($r['feedback']) ? htmlspecialchars($r['feedback']) : "No feedback given."; ?>
        </p>

      </div>
    <?php } ?>

  <?php } ?>

</div>

</body>
</html>