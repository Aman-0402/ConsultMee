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
  <title><?php echo htmlspecialchars($consultant['name']); ?> | ConsultME</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { cobalt: '#2563eb', accent: '#0ea5e9' },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        }
      }
    }
  </script>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">

  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%); min-height: 100vh; }

    .glass-card {
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 24px;
      position: relative;
      overflow: hidden;
    }
    .glass-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.15), transparent 65%);
      pointer-events: none;
    }

    .info-box {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      padding: 14px 16px;
      transition: all 0.2s;
    }
    .info-box:hover {
      transform: translateY(-2px);
      border-color: rgba(37,99,235,0.3);
      background: rgba(37,99,235,0.08);
    }

    .review-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.09);
      border-radius: 18px;
      padding: 20px;
      transition: all 0.2s;
    }
    .review-card:hover {
      transform: translateY(-2px);
      border-color: rgba(37,99,235,0.25);
    }

    .star-filled { color: #f59e0b; }
    .star-empty { color: rgba(255,255,255,0.2); }
  </style>
</head>
<body class="text-white">

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 right-0 z-50 bg-black/20 backdrop-blur-lg border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <a href="https://consultmee.in">
          <img src="./assets/img/logo.png" alt="ConsultME" class="h-9 w-auto">
        </a>
        <div class="hidden md:flex items-center gap-6">
          <a href="https://consultmee.in" class="text-white/70 text-sm hover:text-white transition-colors">Home</a>
          <a href="./services" class="text-white/70 text-sm hover:text-white transition-colors">Services</a>
          <a href="./about" class="text-white/70 text-sm hover:text-white transition-colors">About</a>
          <a href="./contact" class="text-white/70 text-sm hover:text-white transition-colors">Contact</a>
        </div>
        <a href="dashboard.php" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">
          <i class="bi bi-grid-fill me-1"></i> Dashboard
        </a>
      </div>
    </div>
  </nav>

  <!-- MAIN -->
  <main class="max-w-5xl mx-auto px-4 pt-28 pb-16">

    <!-- PROFILE CARD -->
    <div class="glass-card p-8 mb-8 shadow-2xl">
      <div class="flex flex-col lg:flex-row gap-8 items-start">

        <!-- Left: Avatar -->
        <div class="flex flex-col items-center lg:w-56 shrink-0">
          <div class="relative">
            <img
              src="./uploads/consultants/<?php echo !empty($consultant['profile_img']) ? htmlspecialchars($consultant['profile_img']) : 'default.png'; ?>"
              alt="<?php echo htmlspecialchars($consultant['name']); ?>"
              class="w-44 h-44 rounded-full object-cover border-4 border-cobalt/50 shadow-2xl shadow-blue-500/20 hover:scale-105 transition-transform"
            >
            <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-400 border-2 border-white/20 rounded-full"></span>
          </div>

          <!-- Rating badge -->
          <div class="mt-4 flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/25 text-amber-400">
            <i class="bi bi-star-fill text-sm"></i>
            <span class="font-bold text-sm"><?php echo $avgRating ?: 'N/A'; ?>/5</span>
            <span class="text-amber-400/60 text-xs">(<?php echo $totalReviews; ?>)</span>
          </div>
        </div>

        <!-- Right: Info -->
        <div class="flex-1 min-w-0">

          <!-- Name + Username -->
          <h1 class="text-3xl font-black tracking-tight mb-0.5">
            <?php echo htmlspecialchars($consultant['name']); ?>
          </h1>
          <p class="text-white/45 font-medium mb-4">@<?php echo htmlspecialchars($consultant['username']); ?></p>

          <!-- Badges -->
          <div class="flex flex-wrap gap-2 mb-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-cobalt to-accent text-white text-xs font-bold shadow-lg shadow-blue-500/20">
              <i class="bi bi-person-badge-fill"></i>
              <?php echo htmlspecialchars($consultant['identity'] ?? 'Consultant'); ?>
            </span>
            <?php if (!empty($consultant['area_of_expertise'])) { ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-bold">
              <i class="bi bi-briefcase-fill"></i>
              <?php echo htmlspecialchars($consultant['area_of_expertise']); ?>
            </span>
            <?php } ?>
          </div>

          <!-- Info Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="info-box">
              <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="bi bi-geo-alt-fill me-1 text-cobalt"></i>State
              </p>
              <p class="text-white font-bold text-sm"><?php echo htmlspecialchars($consultant['state'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-box">
              <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="bi bi-currency-rupee me-1 text-cobalt"></i>Hourly Rate
              </p>
              <p class="text-white font-bold text-sm">
                ₹<?php echo htmlspecialchars($consultant['hourly_rate'] ?? 'N/A'); ?>/Hour
              </p>
            </div>
            <div class="info-box">
              <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="bi bi-graph-up-arrow me-1 text-cobalt"></i>Experience
              </p>
              <p class="text-white font-bold text-sm"><?php echo htmlspecialchars($consultant['experience'] ?? 'N/A'); ?></p>
            </div>
          </div>

          <!-- Bio -->
          <div>
            <h3 class="font-bold text-white mb-2 flex items-center gap-2">
              <i class="bi bi-info-circle-fill text-cobalt"></i> About
            </h3>
            <p class="text-white/60 leading-relaxed text-sm">
              <?php echo !empty($consultant['bio']) ? nl2br(htmlspecialchars($consultant['bio'])) : 'No bio available.'; ?>
            </p>
          </div>

        </div>
      </div>
    </div>

    <!-- BOOK APPOINTMENT CTA -->
    <div class="glass-card p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div>
        <h3 class="font-bold text-lg">Ready to consult?</h3>
        <p class="text-white/45 text-sm">Book a session with <?php echo htmlspecialchars($consultant['name']); ?> today.</p>
      </div>
      <a href="dashboard.php#consultant-list"
         class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-bold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 whitespace-nowrap">
        <i class="bi bi-calendar-check me-2"></i>Book Appointment
      </a>
    </div>

    <!-- REVIEWS -->
    <div>
      <h2 class="text-xl font-black mb-5 flex items-center gap-2">
        <i class="bi bi-chat-square-quote-fill text-cobalt"></i>
        Ratings &amp; Reviews
        <span class="text-sm font-semibold text-white/35 ml-1">(<?php echo $totalReviews; ?>)</span>
      </h2>

      <?php if ($totalReviews == 0) { ?>
        <div class="glass-card p-8 text-center">
          <i class="bi bi-chat-left-dots text-white/20 text-4xl block mb-3"></i>
          <p class="text-white/45 font-semibold">No reviews yet. Be the first to review this consultant!</p>
        </div>
      <?php } else { ?>

        <div class="flex flex-col gap-4">
          <?php while ($r = $reviews->fetch_assoc()) { ?>
          <div class="review-card">

            <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
              <span class="font-bold text-sm flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-cobalt/30 flex items-center justify-center text-xs font-black text-cobalt uppercase">
                  <?php echo strtoupper(substr($r['user_username'], 0, 1)); ?>
                </span>
                <?php echo htmlspecialchars($r['user_username']); ?>
              </span>
              <span class="text-white/35 text-xs font-medium">
                <i class="bi bi-calendar3 me-1"></i>
                <?php echo date("d M Y", strtotime($r['created_at'])); ?>
              </span>
            </div>

            <!-- Stars -->
            <div class="flex items-center gap-1 mb-3">
              <?php
                $stars = (int)$r['rating'];
                for ($i = 1; $i <= 5; $i++) {
                  echo $i <= $stars
                    ? '<i class="bi bi-star-fill star-filled"></i>'
                    : '<i class="bi bi-star star-empty"></i>';
                }
              ?>
              <span class="text-white/35 text-xs ml-1 font-semibold">(<?php echo $stars; ?>/5)</span>
            </div>

            <p class="text-white/55 text-sm leading-relaxed">
              <?php echo !empty($r['feedback']) ? htmlspecialchars($r['feedback']) : 'No feedback given.'; ?>
            </p>

          </div>
          <?php } ?>
        </div>

      <?php } ?>
    </div>

  </main>

</body>
</html>
