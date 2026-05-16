<?php
session_start();

// === Helper Function ===
function redirectWithMessage($message, $type = "error") {
    header("Location: clogin.php?msg=" . urlencode($message) . "&type=" . $type);
    exit;
}

// === Handle POST Request ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        redirectWithMessage("Please fill in all fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage("Invalid email format.");
    }

    // === Database Connection ===
    $conn = new mysqli("localhost", "yibnyzre_cme_admin", "As792002@", "yibnyzre_cme");
    if ($conn->connect_error) {
        redirectWithMessage("Database connection failed.");
    }

    // === Fetch Consultant by Email ===
    $stmt = $conn->prepare(
        "SELECT name, username, area_of_expertise, bio, experience, phone, email, password, identity
         FROM consultants WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result(
            $name,
            $username,
            $area_of_expertise,
            $bio,
            $experience,
            $phone,
            $email,
            $hashed_password,
            $identity
        );
        $stmt->fetch();

        // === Verify Password ===
        if (password_verify($password, $hashed_password)) {
            $_SESSION['consultant'] = [
                'name' => $name,
                'username' => $username,
                'area_of_expertise' => $area_of_expertise,
                'bio' => $bio,
                'experience' => $experience,
                'phone' => $phone,
                'email' => $email,
                'identity' => $identity
            ];

            header("Location: consultant_dashboard.php");
            exit;
        } else {
            $stmt->close();
            $conn->close();
            redirectWithMessage("Incorrect password.");
        }
    } else {
        $stmt->close();
        $conn->close();
        redirectWithMessage("No consultant found with that email.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Consultant Login | ConsultME</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            cobalt: '#2563eb',
            accent: '#0ea5e9',
          },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        }
      }
    }
  </script>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">

  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body {
      background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%);
      min-height: 100vh;
    }
    .glass-card { position: relative; overflow: hidden; }
    .glass-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background: radial-gradient(ellipse at 30% 0%, rgba(37,99,235,0.18), transparent 70%);
      pointer-events: none;
    }
    .form-input {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px;
      color: white;
      padding: 13px 16px;
      width: 100%;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      font-family: 'Inter', sans-serif;
      font-size: 0.95rem;
    }
    .form-input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.22);
    }
    .form-input::placeholder { color: rgba(255,255,255,0.3); }
  </style>
</head>
<body class="text-white flex flex-col min-h-screen">

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
          <a href="./about" class="text-white/70 text-sm hover:text-white transition-colors">About Us</a>
          <a href="./contact" class="text-white/70 text-sm hover:text-white transition-colors">Contact</a>
        </div>
        <div class="flex items-center gap-3">
          <a href="csignup.php" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Sign Up</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- LOGIN CARD -->
  <main class="flex-1 flex items-center justify-center px-4 pt-24 pb-16">
    <div class="w-full max-w-md">

      <!-- Logo + title -->
      <div class="text-center mb-8">
        <img src="./assets/img/logo.png" alt="ConsultME" class="h-12 w-auto mx-auto mb-5">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-medium mb-4">
          <i class="bi bi-briefcase-fill"></i> Consultant Portal
        </div>
        <h1 class="text-2xl font-black mb-1">Consultant Login</h1>
        <p class="text-white/50 text-sm">Sign in to your consultant dashboard</p>
      </div>

      <!-- Card -->
      <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">

        <!-- Message box -->
        <div id="messageBox" class="mb-4 text-center text-sm font-medium hidden"></div>

        <form action="clogin.php" method="POST" class="flex flex-col gap-5">

          <div>
            <label class="block text-sm font-semibold mb-2 text-white/80">Email Address</label>
            <div class="relative">
              <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-white/30"></i>
              <input type="email" name="email" class="form-input pl-11" placeholder="your@email.com" required>
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-sm font-semibold text-white/80">Password</label>
              <a href="./cforgot_password" class="text-xs text-accent hover:text-white transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
              <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/30"></i>
              <input type="password" name="password" id="passwordField" class="form-input pl-11 pr-11" placeholder="Enter your password" required>
              <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/70 transition-colors">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>

          <button type="submit" name="login" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 mt-2">
            Sign In <i class="bi bi-arrow-right ml-1"></i>
          </button>

        </form>

        <div class="mt-6 flex flex-col gap-3 text-center">
          <p class="text-white/50 text-sm">
            Don't have an account? <a href="csignup.php" class="text-accent hover:text-white font-medium transition-colors">Sign up</a>
          </p>
          <p class="text-white/30 text-xs">
            Are you a client? <a href="./login.php" class="text-white/50 hover:text-white transition-colors">User Login</a>
          </p>
        </div>

      </div>
    </div>
  </main>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const type = urlParams.get('type');
    if (msg) {
      const box = document.getElementById('messageBox');
      box.classList.remove('hidden');
      box.innerHTML = decodeURIComponent(msg);
      box.style.color = (type === 'error') ? '#f87171' : '#34d399';
      box.style.background = (type === 'error') ? 'rgba(239,68,68,0.1)' : 'rgba(52,211,153,0.1)';
      box.style.padding = '10px 16px';
      box.style.borderRadius = '10px';
      box.style.border = (type === 'error') ? '1px solid rgba(239,68,68,0.3)' : '1px solid rgba(52,211,153,0.3)';
    }
    function togglePassword() {
      const field = document.getElementById('passwordField');
      const icon = document.getElementById('eyeIcon');
      if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        field.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }
  </script>
</body>
</html>
