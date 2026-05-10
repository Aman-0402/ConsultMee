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

        // ✅ SEND WELCOME MAIL AFTER SUCCESSFUL REGISTRATION (Anti-Spam Improved)

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

        // ✅ Anti-Spam Headers
        $userHeaders  = "MIME-Version: 1.0\r\n";
        $userHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
        $userHeaders .= "From: $fromName <$fromEmail>\r\n";
        $userHeaders .= "Reply-To: info@consultmee.in\r\n";
        $userHeaders .= "Return-Path: $fromEmail\r\n";
        $userHeaders .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $userHeaders .= "X-Priority: 3\r\n";

        // Send Mail (Envelope sender improves deliverability)
        @mail($email, $userSubject, $userBody, $userHeaders, "-f $fromEmail");

        // Close DB and Redirect
        $insert_stmt->close();
        $conn->close();

        redirectWithMessage("Account created successfully. <a href='login.php'>Login now</a>", "success");

    } else {
        $insert_stmt->close();
        $conn->close();
        redirectWithMessage("Error saving data. Please try again.", "error");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ConsultME | Signup</title>

  <!-- Primary Meta Tags -->
  <meta name="title" content="ConsultME | Professional Consultancy, Mentorship & Advisory Platform" />
  <meta name="description" content="ConsultME is India's leading e-marketplace for consultancy, mentorship, and advisory services. Connect with verified experts in Business, Legal, AI, Finance, Healthcare, and Media to grow faster and smarter." />
  <meta name="keywords" content="consultancy services, mentorship platform, advisory services, business consultants, AI consulting, finance advisory, legal compliance experts, healthcare consultants, media consultants, startup mentorship, professional advisory India" />
  <meta name="author" content="ConsultME Team" />
  <meta name="robots" content="index, follow" />
  <meta name="language" content="English" />
  <meta name="revisit-after" content="7 days" />
  <!-- Open Graph -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.consultmee.in/" />
  <meta property="og:title" content="ConsultME | India’s Top Consultancy & Mentorship Platform" />
  <meta property="og:description" content="Find experts across Business, Legal, Finance, AI, Media, and Healthcare domains. ConsultME helps you get personalized solutions and professional mentorship instantly." />

  <!-- Bootstrap & icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="/assets/img/logox.ico">
  <link rel="icon" href="/favicon.ico">

  <!-- External stylesheet -->
  <link rel="stylesheet" href="./CSS/main.css">
  <link rel="stylesheet" href="./CSS/signup.css">
</head>

<body>

    <!-- Navigation bar start -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
    
        <!-- Logo -->
        <a class="navbar-brand" href="https://consultmee.in">
          <img src="./assets/img/logo.png" alt="Logo" class="navbar-logo">
        </a>
    
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    
        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    
          <!-- Nav links (right after logo) -->
          <div class="navbar-nav text-center">
            <a class="nav-link active" aria-current="page" href="https://consultmee.in">Home</a>
            <a class="nav-link" href="./services">Services</a>
            <a class="nav-link" href="./about">About us</a>
            <a class="nav-link" href="./contact">Contact us</a>
          </div>
    
          <!-- Auth Buttons (right aligned) -->
          <div class="d-flex gap-2 justify-content-center ms-lg-auto mt-lg-0 mt-3">
            <a href="create-account" class="btn btn-outline-primary">Signup</a>
            <a href="login-account" class="btn btn-outline-dark">Login</a>
          </div>
    
        </div>
      </div>
    </nav>
    <!-- Navigation bar end -->

<!--Signup section starts-->
  <div class="container">
    <h2>Signup - ConsultME</h2>
    <div id="messageBox"></div>

    <div class="card" style="width: 35rem;">
        <div class="card-body">
            <form method="post" action="./signup.php">
                <label>Full Name *</label>
                <input type="text" name="full_name" required>
          
                <label>Phone Number *</label>
                <input type="text" name="phone" pattern="[0-9]{10}" required>
          
                <label>Username *</label>
                <input type="text" name="username" placeholder="e.g. xyz1234" required>
          
                <label>Email *</label>
                <input type="email" name="email" required>
                
                <label>Identity *</label>
                <select name="identity" placeholder="Select Identity" required>
                    <option value="">Select Identity</option>
                    <option>Enterprise</option>
                    <option>MSME</option>
                    <option>Professional</option>
                    <option>Self-Employed</option>
                    <option>Individual</option>
                </select>

                <label>State *</label>
                <select name="state" placeholder="State" required>
                  <option value="">Select State</option>
                  <option value="Andhra Pradesh">Andhra Pradesh</option>
                  <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                  <option value="Assam">Assam</option>
                  <option value="Bihar">Bihar</option>
                  <option value="Chhattisgarh">Chhattisgarh</option>
                  <option value="Goa">Goa</option>
                  <option value="Gujarat">Gujarat</option>
                  <option value="Haryana">Haryana</option>
                  <option value="Himachal Pradesh">Himachal Pradesh</option>
                  <option value="Jharkhand">Jharkhand</option>
                  <option value="Karnataka">Karnataka</option>
                  <option value="Kerala">Kerala</option>
                  <option value="Madhya Pradesh">Madhya Pradesh</option>
                  <option value="Maharashtra">Maharashtra</option>
                  <option value="Manipur">Manipur</option>
                  <option value="Meghalaya">Meghalaya</option>
                  <option value="Mizoram">Mizoram</option>
                  <option value="Nagaland">Nagaland</option>
                  <option value="Odisha">Odisha</option>
                  <option value="Punjab">Punjab</option>
                  <option value="Rajasthan">Rajasthan</option>
                  <option value="Sikkim">Sikkim</option>
                  <option value="Tamil Nadu">Tamil Nadu</option>
                  <option value="Telangana">Telangana</option>
                  <option value="Tripura">Tripura</option>
                  <option value="Uttar Pradesh">Uttar Pradesh</option>
                  <option value="Uttarakhand">Uttarakhand</option>
                  <option value="West Bengal">West Bengal</option>
                  <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                  <option value="Chandigarh">Chandigarh</option>
                  <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                  <option value="Delhi">Delhi</option>
                  <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                  <option value="Ladakh">Ladakh</option>
                  <option value="Lakshadweep">Lakshadweep</option>
                  <option value="Puducherry">Puducherry</option>
                </select>

                <label>Area of Interest 1 *</label>
                <select name="industry1" placeholder="Industry" required>
                  <option value="">Select Industry</option>
                  <option value="Agriculture">Agriculture</option>
                  <option value="Artificial Intelligence">Artificial Intelligence</option>
                  <option value="Astronomy">Astronomy</option>
                  <option value="Automobiles and Auto Components">Automobiles and Auto Components</option>
                  <option value="Business & Management">Business & Management</option>
                  <option value="Capital Goods">Capital Goods</option>
                  <option value="Chemicals">Chemicals</option>
                  <option value="Construction">Construction</option>
                  <option value="Consumer Durables">Consumer Durables</option>
                  <option value="Consumer Services">Consumer Services</option>
                  <option value="Defence">Defence</option>
                  <option value="Diversified">Diversified</option>
                  <option value="Education">Education</option>
                  <option value="Energy">Energy</option>
                  <option value="Fast Moving Consumer Goods(FMCG)">Fast Moving Consumer Goods(FMCG)</option>
                  <option value="Finance">Finance</option>
                  <option value="Food, Beverage & Tobacco">Food, Beverage & Tobacco</option>
                  <option value="Healthcare">Healthcare</option>
                  <option value="Hospitality, Tourism & Leisure">Hospitality, Tourism & Leisure</option>
                  <option value="Technology">Technology</option>
                  <option value="Law & Order">Law & Order</option>
                  <option value="Media, Entertainment & Publication">Media, Entertainment & Publication</option>
                  <option value="Metals & Mining">Metals & Mining</option>
                  <option value="Oil, Gas & Consumable Fuels">Oil, Gas & Consumable Fuels</option>
                  <option value="Power">Power</option>
                  <option value="Realty">Realty</option>
                  <option value="Services">Services</option>
                  <option value="Telecommunication">Telecommunication</option>
                  <option value="Textile">Textile</option>
                  <option value="Transport">Transport</option>
                </select>
                
                <label>Area of Interest 2 *</label>
                <select name="industry2" placeholder="Industry">
                  <option value="">Select Industry</option>
                  <option value="Agriculture">Agriculture</option>
                  <option value="Artificial Intelligence">Artificial Intelligence</option>
                  <option value="Astronomy">Astronomy</option>
                  <option value="Automobiles and Auto Components">Automobiles and Auto Components</option>
                  <option value="Business & Management">Business & Management</option>
                  <option value="Capital Goods">Capital Goods</option>
                  <option value="Chemicals">Chemicals</option>
                  <option value="Construction">Construction</option>
                  <option value="Consumer Durables">Consumer Durables</option>
                  <option value="Consumer Services">Consumer Services</option>
                  <option value="Defence">Defence</option>
                  <option value="Diversified">Diversified</option>
                  <option value="Education">Education</option>
                  <option value="Energy">Energy</option>
                  <option value="Fast Moving Consumer Goods(FMCG)">Fast Moving Consumer Goods(FMCG)</option>
                  <option value="Finance">Finance</option>
                  <option value="Food, Beverage & Tobacco">Food, Beverage & Tobacco</option>
                  <option value="Healthcare">Healthcare</option>
                  <option value="Hospitality, Tourism & Leisure">Hospitality, Tourism & Leisure</option>
                  <option value="Technology">Technology</option>
                  <option value="Law & Order">Law & Order</option>
                  <option value="Media, Entertainment & Publication">Media, Entertainment & Publication</option>
                  <option value="Metals & Mining">Metals & Mining</option>
                  <option value="Oil, Gas & Consumable Fuels">Oil, Gas & Consumable Fuels</option>
                  <option value="Power">Power</option>
                  <option value="Realty">Realty</option>
                  <option value="Services">Services</option>
                  <option value="Telecommunication">Telecommunication</option>
                  <option value="Textile">Textile</option>
                  <option value="Transport">Transport</option>
                </select>
          
                <label>Area of Interest 3 *</label>
                <select name="industry3" placeholder="Industry">
                  <option value="">Select Industry</option>
                  <option value="Agriculture">Agriculture</option>
                  <option value="Artificial Intelligence">Artificial Intelligence</option>
                  <option value="Astronomy">Astronomy</option>
                  <option value="Automobiles and Auto Components">Automobiles and Auto Components</option>
                  <option value="Business & Management">Business & Management</option>
                  <option value="Capital Goods">Capital Goods</option>
                  <option value="Chemicals">Chemicals</option>
                  <option value="Construction">Construction</option>
                  <option value="Consumer Durables">Consumer Durables</option>
                  <option value="Consumer Services">Consumer Services</option>
                  <option value="Defence">Defence</option>
                  <option value="Diversified">Diversified</option>
                  <option value="Education">Education</option>
                  <option value="Energy">Energy</option>
                  <option value="Fast Moving Consumer Goods(FMCG)">Fast Moving Consumer Goods(FMCG)</option>
                  <option value="Finance">Finance</option>
                  <option value="Food, Beverage & Tobacco">Food, Beverage & Tobacco</option>
                  <option value="Healthcare">Healthcare</option>
                  <option value="Hospitality, Tourism & Leisure">Hospitality, Tourism & Leisure</option>
                  <option value="Technology">Technology</option>
                  <option value="Law & Order">Law & Order</option>
                  <option value="Media, Entertainment & Publication">Media, Entertainment & Publication</option>
                  <option value="Metals & Mining">Metals & Mining</option>
                  <option value="Oil, Gas & Consumable Fuels">Oil, Gas & Consumable Fuels</option>
                  <option value="Power">Power</option>
                  <option value="Realty">Realty</option>
                  <option value="Services">Services</option>
                  <option value="Telecommunication">Telecommunication</option>
                  <option value="Textile">Textile</option>
                  <option value="Transport">Transport</option>
                </select>
          
                <label>Password *</label>
                <input type="password" name="password" required>
          
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required>
                    
                <button type="submit" name="signup" style="width: 10rem;">Sign Up</button>
            </form>  
            <div class="link">
              <p>Already have an account? <a href="./login.php">Login</a></p>
            </div>
        </div>
    </div>
  </div>
  <!--Signup Section Ends-->
  <br>
  <br>
  <!-- Footer -->
      <footer style="padding: 40px 0; color: black; background-color:lavender;" role="contentinfo">
        <div class="container">
          <div class="row">
            <div class="col-md-3 mb-4">
              <img src="./assets/img/logo.png" alt="ConsultMe Logo" style="height: 70px;">
              <p class="mt-3" style="font-size: 14px;">
                ConsultMe: Your partner in smart, AI-powered business consulting. Accelerate your growth with innovation-driven solutions.
              </p>
            </div>
            <div class="col-md-3 mb-4">
              <h5 style="color: #1dd186;">Quick Links</h5>
              <ul class="list-unstyled">
                <li><a href="./about" class="text-decoration-none">About Us</a></li>
                <li><a href="./contact" class="text-decoration-none">Contact Us</a></li>
                <li><a href="./services" class="text-decoration-none">Our Services</a></li>
              </ul>
            </div>
            <div class="col-md-3 mb-4">
              <h5 style="color: #1dd186;">Get Involved</h5>
              <ul class="list-unstyled">
                <li><a href="csignup.php" class="text-decoration-none">Become a Freelancer</a></li>
                <li><a href="clogin.php" class="text-decoration-none">Freelancer Login</a></li>
                <li><a href="contact" class="text-decoration-none">Contact</a></li>
              </ul>
            </div>
            <div class="col-md-3 mb-4">
              <h5 style="color: #1dd186;">Contact Us</h5>
              <ul class="list-unstyled">
                <li>
                    <address>
                        <i class="bi bi-geo-alt-fill text-success me-2"></i> 2066 2nd Floor, Nazarbaug Palace 
                        Mandvi, Near Mandvi Gate, Vadodara
                        Gujarat, India 390001
                    </address>
                </li>
                <li><i class="bi bi-telephone-fill text-success me-2"></i> +91 8317818107</li>
                <li><i class="bi bi-envelope-fill text-success me-2"></i> info@consultmee.in</li>
              </ul>
            </div>
          </div>
          <hr>
          <div class="text-center">
            <small>&copy; <span style="color: #1dd186;">ConsultME</span>, All rights reserved.</small>
          </div>
        </div>
      </footer>
    <!--Footer End-->

<script>
const params = new URLSearchParams(window.location.search);
const msg = params.get("msg");
const type = params.get("type");

if (msg) {
  const box = document.getElementById("messageBox");
  box.innerHTML = decodeURIComponent(msg);
  box.className = type === "error" ? "alert alert-danger" : "alert alert-success";
}
</script>

<!--Scroll Pop Up Logic-->
<script>
  const revealElements = document.querySelectorAll(
    '.hero-left h4, .hero-left h1, .hero-desc, .hero-cta, ' +
    '.service-card, .card, .accordion-item, ' +
    '.philosophy-section *, .why-section *, .cta-section, .footer-section *'
  );

  revealElements.forEach(el => el.classList.add('reveal'));

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
        } else {
          entry.target.classList.remove('show'); // enables reverse on scroll up
        }
      });
    },
    {
      threshold: 0.15,
      rootMargin: '0px 0px -60px 0px'
    }
  );

  revealElements.forEach(el => observer.observe(el));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
