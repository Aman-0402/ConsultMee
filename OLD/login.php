<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        redirectWithMessage("Please fill in all fields.", "error");
    }

    // DB connection
    $servername = "localhost";
    $db_username = "yibnyzre_cme_admin";
    $db_password = "As792002@";
    $dbname = "yibnyzre_cme";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        redirectWithMessage("Database connection failed.", "error");
    }

    $stmt = $conn->prepare(
        "SELECT id, full_name, username, phone, email, interest1, interest2, interest3, password, state, identity, profile_img 
         FROM users WHERE email = ?"
    );
    if (!$stmt) {
        redirectWithMessage("SQL error: " . $conn->error, "error");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result(
            $id,
            $full_name,
            $username,
            $phone,
            $email,
            $interest1,
            $interest2,
            $interest3,
            $hashed_password,
            $state,
            $identity,
            $profile_img
        );
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = [
                'id' => $id,
                'full_name' => $full_name,
                'username' => $username,
                'phone' => $phone,
                'email' => $email,
                'interest1' => $interest1,
                'interest2' => $interest2,
                'interest3' => $interest3,
                'state' => $state,
                'identity' => $identity,
                'profile_img' => $profile_img
            ];

            header("Location: dashboard.php");
            exit;
        } else {
            redirectWithMessage("Incorrect password.", "error");
        }
    } else {
        redirectWithMessage("No account found with that email.", "error");
    }

    $stmt->close();
    $conn->close();
}

function redirectWithMessage($msg, $type) {
    header("Location: login.php?msg=" . urlencode($msg) . "&type=" . $type);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/main.css">
    <link rel="stylesheet" href="./CSS/signup.css">
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

    <!--Login Section Starts-->
    <div class="container">
        <h2>Login - ConsultME</h2>
        <div class="card" style="width: 35rem;">
            <div class="card-body">
                <form method="post" action="login.php">
                    <label>Email</label>
                    <input type="email" name="email" required>
        
                    <label>Password</label>
                    <input type="password" name="password" required>

                    <button type="submit" name="login" style="width: 10rem;">Login</button>
                </form>
                <br>
                <div id="messageBox" style="text-align: center;"></div>     
                <div class="link">
                    <p>Don't have an account? <a href="./signup.php">Sign up</a></p>
                </div>
                <div class="link">
                    <p>forgot password? <a href="./forgot_password">Reset</a></p>
                </div>
            </div>
        </div>
    </div>
    <!--Login Section Ends-->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
