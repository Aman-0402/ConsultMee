<?php
function redirectWithMessage($msg, $type) {
    header("Location: signup.php?msg=" . urlencode($msg) . "&type=" . urlencode($type));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $state = $_POST['state'] ?? '';
    $interest1 = $_POST['industry1'] ?? '';
    $interest2 = $_POST['industry2'] ?? null;
    $interest3 = $_POST['industry3'] ?? null;
    $identity = $_POST['identity'] ?? '';

    // === Validation ===
    if (
        empty($full_name) || empty($phone) || empty($username) ||
        empty($email) || empty($password) || empty($confirm_password) ||
        empty($state) || empty($interest1) || empty($identity)
    ) {
        redirectWithMessage("Please fill in all required fields.", "error");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage("Invalid email format.", "error");
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        redirectWithMessage("Phone number must be exactly 10 digits.", "error");
    }

    if ($password !== $confirm_password) {
        redirectWithMessage("Passwords do not match.", "error");
    }

    // === Generate User ID ===
    $name_part = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $full_name), 0, 4));
    $phone_part = substr($phone, -5);
    $user_id = $name_part . $phone_part;

    // === DB Connection ===
    $conn = new mysqli("localhost", "yibnyzre_cme_admin", "As792002@", "yibnyzre_cme");
    if ($conn->connect_error) {
        redirectWithMessage("Database connection failed.", "error");
    }

    // === Check Existing User ===
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        redirectWithMessage("Username or email already taken.", "error");
    }

    $stmt->close();

    // === Insert User ===
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_stmt = $conn->prepare(
        "INSERT INTO users
        (user_id, full_name, phone, username, email, state, password, interest1, interest2, interest3, identity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $insert_stmt->bind_param(
        "sssssssssss",
        $user_id,
        $full_name,
        $phone,
        $username,
        $email,
        $state,
        $hashed_password,
        $interest1,
        $interest2,
        $interest3,
        $identity
    );

    if ($insert_stmt->execute()) {

        // SEND WELCOME MAIL AFTER SUCCESSFUL REGISTRATION
        $fromEmail = "no-reply@consultmee.in";
        $fromName  = "ConsultME Support Team";
        $userSubject = "Welcome to ConsultME - Account Created Successfully!";
        $userBody = "
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <title>Welcome to ConsultME</title>
        </head>
        <body style='margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f6f9;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f6f9; padding:20px 0;'>
            <tr>
              <td align='center'>
                <table width='600' cellpadding='0' cellspacing='0'
                  style='background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0px 4px 15px rgba(0,0,0,0.08); max-width:95%;'>
                  <tr>
                    <td style='background:linear-gradient(90deg,#0f172a,#2563eb); padding:25px; text-align:center;'>
                      <h1 style='margin:0; font-size:24px; color:#ffffff; font-weight:bold;'>Welcome to ConsultME</h1>
                      <p style='margin:8px 0 0; font-size:14px; color:#dbeafe;'>Your consulting journey starts here</p>
                    </td>
                  </tr>
                  <tr>
                    <td style='padding:30px; color:#1f2937;'>
                      <h2 style='margin-top:0; font-size:20px; color:#111827;'>Hello $full_name 👋</h2>
                      <p style='font-size:15px; line-height:1.7; margin:0 0 15px;'>
                        Thank you for registering on <b>ConsultME</b>. We are excited to welcome you onboard.
                      </p>
                      <p style='font-size:15px; line-height:1.7; margin:0 0 15px;'>
                        Your account has been created successfully. You can now login and explore our platform to connect with verified experts
                        for consultancy, mentorship, and advisory services.
                      </p>
                      <p style='font-size:15px; line-height:1.7; margin:0 0 20px;'>
                        We recommend completing your profile to increase your visibility and improve your chances of receiving opportunities.
                      </p>
                      <div style='text-align:center; margin:30px 0;'>
                        <a href='https://consultmee.in/login-account'
                           style='background:#2563eb; color:#ffffff; text-decoration:none; padding:14px 28px; border-radius:8px;
                           font-size:16px; font-weight:bold; display:inline-block;'>
                           Login to Your Account
                        </a>
                      </div>
                      <p style='font-size:14px; line-height:1.7; margin:0 0 15px; color:#374151;'>
                        If you need help or have any questions, simply reply to this email and our team will assist you.
                      </p>
                      <p style='margin:0; font-size:14px; color:#111827;'>
                        Warm Regards, <br>
                        <b>ConsultME Support Team</b><br>
                        <span style='color:#6b7280;'>ConsultME Solutions</span>
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td style='background:#f9fafb; padding:18px; text-align:center; font-size:12px; color:#6b7280;'>
                      © " . date("Y") . " ConsultME Solutions. All rights reserved. <br>
                      <span style='color:#9ca3af;'>This is an automated email. Please do not share your password with anyone.</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>
        ";

        $userHeaders  = "MIME-Version: 1.0\r\n";
        $userHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
        $userHeaders .= "From: $fromName <$fromEmail>\r\n";
        $userHeaders .= "Reply-To: info@consultmee.in\r\n";
        $userHeaders .= "Return-Path: $fromEmail\r\n";
        $userHeaders .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $userHeaders .= "X-Priority: 3\r\n";

        @mail($email, $userSubject, $userBody, $userHeaders, "-f $fromEmail");

        $insert_stmt->close();
        $conn->close();

        redirectWithMessage("Account created successfully. <a href='login.php'>Login now</a>", "success");

    } else {
        $insert_stmt->close();
        $conn->close();
        redirectWithMessage("Error saving data. Please try again.", "error");
    }
}

$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];

$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ConsultME | Sign Up</title>
  <meta name="description" content="ConsultME is India's leading e-marketplace for consultancy, mentorship, and advisory services." />

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
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">

  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%); min-height: 100vh; }
    .glass-card { position: relative; overflow: hidden; }
    .glass-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background: radial-gradient(ellipse at 30% 0%, rgba(37,99,235,0.15), transparent 70%);
      pointer-events: none;
    }
    .form-input, .form-select {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px;
      color: white;
      padding: 12px 14px;
      width: 100%;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      font-family: 'Inter', sans-serif;
      font-size: 0.9rem;
    }
    .form-input:focus, .form-select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
    }
    .form-input::placeholder { color: rgba(255,255,255,0.3); }
    .form-select option { background: #0d1a3a; color: white; }
    label { display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255,255,255,0.75); margin-bottom: 6px; }
    .field { display: flex; flex-direction: column; gap: 0; }
  </style>
</head>
<body class="text-white">

  <!-- NAVBAR -->
  <nav class="fixed top-0 left-0 right-0 z-50 bg-black/20 backdrop-blur-lg border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <a href="https://consultmee.in"><img src="./assets/img/logo.png" alt="ConsultME" class="h-9 w-auto"></a>
        <div class="hidden md:flex items-center gap-6">
          <a href="https://consultmee.in" class="text-white/70 text-sm hover:text-white transition-colors">Home</a>
          <a href="./services" class="text-white/70 text-sm hover:text-white transition-colors">Services</a>
          <a href="./about" class="text-white/70 text-sm hover:text-white transition-colors">About</a>
          <a href="./contact" class="text-white/70 text-sm hover:text-white transition-colors">Contact</a>
        </div>
        <a href="login.php" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Login</a>
      </div>
    </div>
  </nav>

  <!-- SIGNUP CARD -->
  <main class="flex justify-center px-4 pt-28 pb-16">
    <div class="w-full max-w-xl">

      <div class="text-center mb-8">
        <img src="./assets/img/logo.png" alt="ConsultME" class="h-12 w-auto mx-auto mb-5">
        <h1 class="text-2xl font-black mb-1">Create your account</h1>
        <p class="text-white/50 text-sm">Join ConsultME and connect with industry experts</p>
      </div>

      <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">

        <div id="messageBox" class="mb-5 text-center text-sm font-medium hidden"></div>

        <form method="post" action="./signup.php">
          <div class="grid sm:grid-cols-2 gap-4 mb-4">
            <div class="field">
              <label>Full Name *</label>
              <input type="text" name="full_name" class="form-input" placeholder="John Doe" required>
            </div>
            <div class="field">
              <label>Phone Number *</label>
              <input type="text" name="phone" class="form-input" pattern="[0-9]{10}" placeholder="10 digit number" required>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4 mb-4">
            <div class="field">
              <label>Username *</label>
              <input type="text" name="username" class="form-input" placeholder="e.g. xyz1234" required>
            </div>
            <div class="field">
              <label>Email *</label>
              <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4 mb-4">
            <div class="field">
              <label>Identity *</label>
              <select name="identity" class="form-select" required>
                <option value="">Select Identity</option>
                <option>Enterprise</option>
                <option>MSME</option>
                <option>Professional</option>
                <option>Self-Employed</option>
                <option>Individual</option>
              </select>
            </div>
            <div class="field">
              <label>State *</label>
              <select name="state" class="form-select" required>
                <option value="">Select State</option>
                <?php foreach ($states as $s) echo "<option value='$s'>$s</option>"; ?>
              </select>
            </div>
          </div>

          <div class="field mb-4">
            <label>Area of Interest 1 *</label>
            <select name="industry1" class="form-select" required>
              <option value="">Select Industry</option>
              <?php foreach ($industries as $ind) echo "<option value='$ind'>$ind</option>"; ?>
            </select>
          </div>

          <div class="grid sm:grid-cols-2 gap-4 mb-4">
            <div class="field">
              <label>Area of Interest 2</label>
              <select name="industry2" class="form-select">
                <option value="">Select Industry</option>
                <?php foreach ($industries as $ind) echo "<option value='$ind'>$ind</option>"; ?>
              </select>
            </div>
            <div class="field">
              <label>Area of Interest 3</label>
              <select name="industry3" class="form-select">
                <option value="">Select Industry</option>
                <?php foreach ($industries as $ind) echo "<option value='$ind'>$ind</option>"; ?>
              </select>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4 mb-6">
            <div class="field">
              <label>Password *</label>
              <input type="password" name="password" class="form-input" placeholder="Create password" required>
            </div>
            <div class="field">
              <label>Confirm Password *</label>
              <input type="password" name="confirm_password" class="form-input" placeholder="Confirm password" required>
            </div>
          </div>

          <button type="submit" name="signup" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
            Create Account <i class="bi bi-arrow-right ml-1"></i>
          </button>
        </form>

        <p class="text-center text-white/50 text-sm mt-5">
          Already have an account? <a href="./login.php" class="text-accent hover:text-white font-medium transition-colors">Login</a>
        </p>

      </div>
    </div>
  </main>

  <script>
    const params = new URLSearchParams(window.location.search);
    const msg = params.get("msg");
    const type = params.get("type");
    if (msg) {
      const box = document.getElementById("messageBox");
      box.classList.remove("hidden");
      box.innerHTML = decodeURIComponent(msg);
      box.style.color = (type === "error") ? '#f87171' : '#34d399';
      box.style.background = (type === "error") ? 'rgba(239,68,68,0.1)' : 'rgba(52,211,153,0.1)';
      box.style.padding = '10px 16px';
      box.style.borderRadius = '10px';
      box.style.border = (type === "error") ? '1px solid rgba(239,68,68,0.3)' : '1px solid rgba(52,211,153,0.3)';
    }
  </script>
</body>
</html>
