<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Trimmed input values
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

    // Basic validation
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

        // DB connection
        $conn = new mysqli("localhost", "yibnyzre_cme_admin", "As792002@", "yibnyzre_cme");
        if ($conn->connect_error) {
            redirectWithMessage("Database connection failed.", "error");
        }

        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM consultants WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            redirectWithMessage("Username or Email already registered.", "error");
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert consultant
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Consultant Registration</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link rel="stylesheet" href="./CSS/main.css">
  <link rel="stylesheet" href="./CSS/clogin.css">
  <link rel="icon" type="image/x-icon" href="/assets/img/logox.ico">
  <link rel="icon" href="/favicon.ico">
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

  <!--Consultant Registration Starts-->
  <div class="container">
    <h2>Freelancer Registration</h2>
    <div class="card" style="width: 35rem;">
      <div class="card-body">
        <form action="csignup.php" method="POST">
          <div id="messageBox" style="text-align: center;"></div>
          <label>Full Name *</label>
          <input type="text" name="full_name" placeholder="Jone Doe" required>
          <label>Username *</label>
          <input type="text" name="username" placeholder="e.g. xyz123" required>
          <label>Identity *</label>
          <select name="identity">
              <option>--Select--</option>
              <option>Consultant</option>
              <option>Trainer</option>
              <option>Mentor</option>
              <option>Expert</option>
              <option>Firm</option>
          </select>
          <label>Working Industry *</label>
          <select name="industry" required>
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
          <label>State *</label>
          <select name="State" required>
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
  
          <textarea name="bio" id="bio" rows="3" placeholder="Bio * (Max 20 words)" required></textarea>
          <p id="bio-count">0 / 20 words</p>
        
          <textarea name="experience" id="experience" rows="3" placeholder="Experience * (Max 100 words)" required></textarea>
          <p id="exp-count">0 / 100 words</p>
          
          <input type="text" name="phone" placeholder="Phone Number *" required>
          <input type="email" name="email" placeholder="Email *" required>
          <input type="password" name="password" id="password" placeholder="Password *" required>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password *" required>
          <small id="password_error" style="color:red;"></small>
          <button type="submit" name="register">Register</button>
        </form>     
        <div class="link">
          <p>Already have an account? <a href="clogin.php">Login</a></p>
        </div>
      </div>
  </div>
  </div>
  <!--Consultation Registration Ends-->
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
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const type = urlParams.get('type');

    if (msg) {
      const box = document.getElementById('messageBox');
      box.innerHTML = decodeURIComponent(msg);
      box.style.color = (type === 'error') ? 'red' : 'green';
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
    <script>
        function limitWords(textareaId, counterId, maxWords) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);
        
            textarea.addEventListener("input", function () {
                let words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
        
                if (words.length > maxWords) {
                    textarea.value = words.slice(0, maxWords).join(" ");
                    words = textarea.value.split(/\s+/);
                }
        
                counter.textContent = words.length + " / " + maxWords + " words";
            });
        }
        
        limitWords("bio", "bio-count", 20);
        limitWords("experience", "exp-count", 100);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>