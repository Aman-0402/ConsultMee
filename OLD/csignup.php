<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $industry = trim($_POST['industry']);
    $State = trim($_POST['State']);
    $bio = trim($_POST['bio']);
    $experience = trim($_POST['experience']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $identity = $_POST['identity'];

    if (
        empty($full_name) || empty($username) || empty($industry) || empty($bio) ||
        empty($experience) || empty($phone) || empty($email) || empty($password)
    ) {
        redirectWithMessage("Please fill in all fields.", "error");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage("Invalid email format.", "error");
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        redirectWithMessage("Phone number must be 10 digits.", "error");
    } else {
        $conn = new mysqli("localhost", "yibnyzre_cme_admin", "As792002@", "yibnyzre_cme");
        if ($conn->connect_error) {
            redirectWithMessage("Database connection failed.", "error");
        }

        $stmt = $conn->prepare("SELECT id FROM consultants WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            redirectWithMessage("Username or Email already registered.", "error");
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare(
                "INSERT INTO consultants
                (name, username, area_of_expertise, bio, experience, phone, email, password, State, identity)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $insert->bind_param(
                "ssssssssss",
                $full_name,
                $username,
                $industry,
                $bio,
                $experience,
                $phone,
                $email,
                $hashed_password,
                $State,
                $identity
            );

            if ($insert->execute()) {
                redirectWithMessage(
                    "Registration successful. <a href='clogin.php'>Login here</a>",
                    "success"
                );
            } else {
                redirectWithMessage("Something went wrong. Please try again.", "error");
            }

            $insert->close();
        }

        $stmt->close();
        $conn->close();
    }
}

function redirectWithMessage($msg, $type) {
    header("Location: csignup.php?msg=" . urlencode($msg) . "&type=" . $type);
    exit;
}

$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];

$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Consultant Registration | ConsultME</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    .form-input, .form-select, .form-textarea {
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
    .form-input:focus, .form-select:focus, .form-textarea:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: rgba(255,255,255,0.3); }
    .form-select option { background: #0d1a3a; color: white; }
    .form-textarea { resize: vertical; }
    label { display: block; font-size: 0.85rem; font-weight: 600; color: rgba(255,255,255,0.75); margin-bottom: 6px; }
    .word-count { font-size: 0.75rem; color: rgba(255,255,255,0.35); margin-top: 4px; text-align: right; }
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
        <a href="clogin.php" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Login</a>
      </div>
    </div>
  </nav>

  <!-- SIGNUP CARD -->
  <main class="flex justify-center px-4 pt-28 pb-16">
    <div class="w-full max-w-xl">

      <div class="text-center mb-8">
        <img src="./assets/img/logo.png" alt="ConsultME" class="h-12 w-auto mx-auto mb-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-medium mb-4">
          <i class="bi bi-briefcase-fill"></i> Consultant Portal
        </div>
        <h1 class="text-2xl font-black mb-1">Join as a Consultant</h1>
        <p class="text-white/50 text-sm">Create your consultant profile on ConsultME</p>
      </div>

      <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">

        <div id="messageBox" class="mb-5 text-center text-sm font-medium hidden"></div>

        <form action="csignup.php" method="POST" class="flex flex-col gap-4">

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label>Full Name *</label>
              <input type="text" name="full_name" class="form-input" placeholder="John Doe" required>
            </div>
            <div>
              <label>Username *</label>
              <input type="text" name="username" class="form-input" placeholder="e.g. xyz123" required>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label>Identity *</label>
              <select name="identity" class="form-select">
                <option>--Select--</option>
                <option>Consultant</option>
                <option>Trainer</option>
                <option>Mentor</option>
                <option>Expert</option>
                <option>Firm</option>
              </select>
            </div>
            <div>
              <label>Working Industry *</label>
              <select name="industry" class="form-select" required>
                <option value="">Select Industry</option>
                <?php foreach ($industries as $ind) echo "<option value='$ind'>$ind</option>"; ?>
              </select>
            </div>
          </div>

          <div>
            <label>State *</label>
            <select name="State" class="form-select" required>
              <option value="">Select State</option>
              <?php foreach ($states as $s) echo "<option value='$s'>$s</option>"; ?>
            </select>
          </div>

          <div>
            <label>Bio * <span class="text-white/30 font-normal">(Max 20 words)</span></label>
            <textarea name="bio" id="bio" class="form-textarea" rows="2" placeholder="Brief description of who you are..." required></textarea>
            <p class="word-count" id="bio-count">0 / 20 words</p>
          </div>

          <div>
            <label>Experience * <span class="text-white/30 font-normal">(Max 100 words)</span></label>
            <textarea name="experience" id="experience" class="form-textarea" rows="3" placeholder="Describe your professional experience..." required></textarea>
            <p class="word-count" id="exp-count">0 / 100 words</p>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label>Phone Number *</label>
              <input type="text" name="phone" class="form-input" placeholder="10 digit number" required>
            </div>
            <div>
              <label>Email *</label>
              <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label>Password *</label>
              <input type="password" name="password" id="password" class="form-input" placeholder="Create password" required>
            </div>
            <div>
              <label>Confirm Password *</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Confirm password" required>
            </div>
          </div>
          <small id="password_error" class="text-red-400 text-xs -mt-2 hidden"></small>

          <button type="submit" name="register" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 mt-2">
            Register as Consultant <i class="bi bi-arrow-right ml-1"></i>
          </button>

        </form>

        <p class="text-center text-white/50 text-sm mt-5">
          Already have an account? <a href="clogin.php" class="text-accent hover:text-white font-medium transition-colors">Login</a>
        </p>

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

    function limitWords(textareaId, counterId, maxWords) {
      const textarea = document.getElementById(textareaId);
      const counter = document.getElementById(counterId);
      textarea.addEventListener("input", function () {
        let words = textarea.value.trim().split(/\s+/).filter(w => w.length > 0);
        if (words.length > maxWords) {
          textarea.value = words.slice(0, maxWords).join(" ");
          words = textarea.value.split(/\s+/);
        }
        counter.textContent = words.length + " / " + maxWords + " words";
      });
    }
    limitWords("bio", "bio-count", 20);
    limitWords("experience", "exp-count", 100);

    document.querySelector('form').addEventListener('submit', function(e) {
      const pass = document.getElementById('password').value;
      const confirm = document.getElementById('confirm_password').value;
      const error = document.getElementById('password_error');
      if (pass !== confirm) {
        e.preventDefault();
        error.classList.remove('hidden');
        error.innerText = "Passwords do not match!";
      }
    });
  </script>
</body>
</html>
