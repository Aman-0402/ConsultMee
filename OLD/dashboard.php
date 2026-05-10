<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user']; // Assuming session has user details
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ConsultME Dashboard</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./CSS/dashboard.css">
</head>

<body>
  <!-- Toggle Sidebar Button -->
    <button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
  <!-- Sidebar -->
  <div class="sidebar">
    <ul class="list-unstyled">
      <a href="https://consultmee.in"><img height="70 auto" width="180 auto" src="./assets/img/logo_bw.png" alt="ConsultME Logo"></a><br>
      <li><a href="#consultants" onclick="showSection('consultants')" class="active"><i class="fa fa-user-tie me-2"></i> Consultants</a></li>
      <li><a href="#categories" onclick="showSection('categories')"><i class="fa fa-list me-2"></i> Categories</a></li>
      <li><a href="#appointments" onclick="showSection('appointments')"><i class="fa fa-calendar-alt me-2"></i> Appointments</a></li>
      <li><a href="#history" onclick="showSection('history')"><i class="fa fa-history me-2"></i> History</a></li>

      <!-- NEW: Projects link -->
      <li><a href="#projects" onclick="showSection('projects')"><i class="fa fa-folder-open me-2"></i> Projects</a></li>

      <li><a href="#profile" onclick="showSection('profile')"><i class="fa fa-user me-2"></i> Profile</a></li>
        <li>
          <a href="logout.php" class="btn btn-danger btn-sm w-100 text-start">
            <i class="fa fa-sign-out-alt"></i> Logout
          </a>
        </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 id="page-title">Consultant List</h2>

    <!-- Sections -->
    <div id="consultants"><div id="consultant-list"></div></div>
    <div id="categories" class="container mt-4" style="display: none;"></div>
    <div id="appointments" style="display:none;"></div>
    
    <!-- NEW: Projects section -->
    <div id="projects" style="display:none;" class="projects-area">
      <!-- Header: Create card on left + tabs on right (responsive) -->
      <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <!-- Create Project Card (keeps same onclick) -->
        <div class="create-card" onclick="openPostProjectModal()" role="button" aria-label="Create a New Project">
          <div class="create-card-inner">
            <span class="create-icon" aria-hidden="true">
              <i class="fa-solid fa-plus"></i>
            </span>
            <div class="create-text">
              <div class="create-title">Create a New Project</div>
              <div class="create-sub">Click to add project details</div>
            </div>
          </div>
        </div>
    
        <!-- Tabs -->
        <div class="projects-tabs btn-group" role="tablist" aria-label="Project tabs">
          <button id="projectsTabActive" class="btn btn-sm tab-btn active" onclick="loadProjects('active',1)">Active Projects</button>
          <button id="projectsTabHistory" class="btn btn-sm tab-btn" onclick="loadProjects('history',1)">Project History</button>
        </div>
      </div>
    
      <!-- Grid / Cards container (your existing logic populates this ID) -->
      <div id="projectsContainer" class="projects-grid mb-4"></div>
    
      <!-- Pagination (your existing logic populates this ID) -->
      <div id="projectsPagination" class="projects-pagination mb-4"></div>
    </div>
    <div id="history" style="display:none;"></div>
    
    <!-- Post Project Modal (simple toggle modal used by main.js) -->
    <div class="modal fade" id="postProjectModal" tabindex="-1" aria-labelledby="postProjectLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
    
          <div class="modal-header">
            <h5 class="modal-title" id="postProjectLabel">Post New Project</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
    
          <form id="postProjectForm" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="mb-2">
                <label class="form-label">Title</label>
                <input type="text" name="title" required class="form-control">
              </div>
    
              <div class="mb-2">
                <label class="form-label">Short Description</label>
                <input type="text" name="short_description" class="form-control" maxlength="512">
              </div>
    
              <div class="mb-2">
                <label class="form-label">Detailed Description</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
              </div>
    
              <div class="mb-2">
                <label class="form-label">Budget</label>
                <input type="text" name="budget" class="form-control">
                <label>Billing Cycle</label>
                <select name="billing" class="form-control">
                    <option>--Select--</option>
                    <option>Hourly</option>
                    <option>Daily</option>
                    <option>Weekly</option>
                    <option>Monthly</option>
                    <option>Quaterly</option>
                    <option>Yearly</option>
                </select>
              </div>
    
              <div class="mb-2">
                <label class="form-label">End Date</label>
                <input type="date" name="expiry_date" class="form-control">
              </div>
    
              <div class="mb-2">
                <label class="form-label">External files Links</label>
                <input type="text" name="links" class="form-control">
              </div>
            </div>
    
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Post</button>
            </div>
    
          </form>
    
        </div>
      </div>
    </div>


    <!-- Profile Section -->
    <div id="profile" style="display:none;">
      <div class="card shadow-lg border-0 p-4 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">

          <!-- Profile Image -->
          <div class="text-center">
            <img id="user-profile-img"
                 src="<?php echo $user['profile_img'] ? './uploads/'.$user['profile_img'] : './assets/img/default.png'; ?>"
                 alt="Profile Image"
                 class="rounded-circle border border-3 border-primary"
                 style="width: 160px; height: 160px; object-fit: cover;">
          </div>

          <!-- Profile Information -->
          <div class="flex-fill">
            <h3 id="user-name" class="fw-bold mb-2"><?php echo htmlspecialchars($user['name']); ?></h3>
            <p class="mb-1 text-muted d-flex align-items-center">
              <i class="fa fa-map-marker-alt me-2"></i>
              <span id="user-state"><?php echo htmlspecialchars($user['state']); ?></span>
            </p>
            <p class="mb-1"><strong>Username:</strong> <span id="user-username"><?php echo htmlspecialchars($user['username']); ?></span></p>
            <p class="mb-1"><strong>Identity:</strong> <span id="user-identity"><?php echo htmlspecialchars($user['identity']); ?></span></p>
            <p class="mb-1"><strong>Phone:</strong> <span id="user-phone"><?php echo htmlspecialchars($user['phone']); ?></span></p>
            <p class="mb-1"><strong>Email:</strong> <span id="user-email"><?php echo htmlspecialchars($user['email']); ?></span></p>
            <p class="mb-0">
              <strong>Interests:</strong>
              <span id="user-interest1"><?php echo htmlspecialchars($user['interest1']); ?></span>,
              <span id="user-interest2"><?php echo htmlspecialchars($user['interest2']); ?></span>,
              <span id="user-interest3"><?php echo htmlspecialchars($user['interest3']); ?></span>
            </p>
          </div>

          <!-- Edit Button -->
          <div class="text-md-end">
            <button type="button"
                    class="btn btn-primary px-4 py-2 rounded-3"
                    onclick="openEditForm()">
              Edit Profile
            </button>
          </div>
        </div>
      </div>

      <!-- Edit Profile Form -->
      <div id="edit-profile-form" class="card mt-3" style="display:none;">
        <div class="card-body">
          <h4>Edit Profile</h4>
          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" id="edit-fullname"
                    value="<?php echo isset($user['full_name']) ? htmlspecialchars($user['full_name']) : ''; ?>"
                    class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone *</label>
              <input type="text" name="phone" id="edit-phone"
                    value="<?php echo htmlspecialchars($user['phone']); ?>"
                    class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Identity *</label>
              <select name="identity" id="edit-identity" class="form-control" required>
                  <option>Enterprise</option>
                  <option>MSME</option>
                  <option>Professional</option>
                  <option>Self-Employed</option>
                  <option>Individual</option>
              </select>
              <script>
                 document.getElementById("edit-identity").value = "<?php echo htmlspecialchars($user['identity']); ?>";
              </script>
            </div>
            <div class="mb-3">
              <label class="form-label">State *</label>
              <select name="state" id="edit-state" class="form-control" required>
                <option value="">--Select State--</option>
                <?php
                $states = ["Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", "Haryana",
                  "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya",
                  "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
                  "Uttar Pradesh", "Uttarakhand", "West Bengal", "Andaman and Nicobar Islands", "Chandigarh",
                  "Dadra and Nagar Haveli and Daman and Diu", "Delhi", "Jammu and Kashmir", "Ladakh", "Lakshadweep", "Puducherry"];
                foreach ($states as $state) {
                  $selected = ($user['state'] == $state) ? 'selected' : '';
                  echo "<option value=\"$state\" $selected>$state</option>";
                }
                ?>
              </select>
            </div>

            <!-- Interests -->
            <?php
            function interestSelect($name, $id, $selectedVal) {
              $industries = ["Agriculture", "Artificial Intelligence", "Astronomy", "Automobiles and Auto Components",
                "Capital Goods", "Chemicals", "Construction", "Consumer Durables", "Consumer Services", "Defence",
                "Diversified", "Education", "Energy", "Fast Moving Consumer Goods(FMCG)", "Finance", "Food, Beverage & Tobacco",
                "Healthcare", "Technology", "Law & Order", "Media, Entertainment & Publication", "Metals & Mining",
                "Oil, Gas & Consumable Fuels", "Power", "Realty", "Services", "Telecommunication", "Textile", "Transport"];
              echo "<select name=\"$name\" id=\"$id\" class=\"form-control\" required>";
              echo "<option value=\"\">Select Industry</option>";
              foreach ($industries as $industry) {
                $selected = ($selectedVal == $industry) ? 'selected' : '';
                echo "<option value=\"$industry\" $selected>$industry</option>";
              }
              echo "</select>";
            }
            ?>
            <div class="mb-3">
              <label class="form-label">Interest 1</label>
              <?php interestSelect('interest1', 'edit-interest1', $user['interest1']); ?>
            </div>
            <div class="mb-3">
              <label class="form-label">Interest 2</label>
              <?php interestSelect('interest2', 'edit-interest2', $user['interest2']); ?>
            </div>
            <div class="mb-3">
              <label class="form-label">Interest 3</label>
              <?php interestSelect('interest3', 'edit-interest3', $user['interest3']); ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Profile Image</label>
              <input type="file" name="profile_image" id="edit-profile-image"
                    class="form-control" accept="image/*">
            </div>
            
            <div id="userUploadProgress" style="display:none; margin-bottom:10px;">
              <div style="width:100%; background:#e9ecef; border-radius:6px; overflow:hidden;">
                <div id="userUploadBar" style="width:0%; height:8px; background:linear-gradient(90deg,#0d6efd,#00c6ff);"></div>
              </div>
              <small id="userUploadText">Uploading... 0%</small>
            </div>

            <div class="text-end">
              <button type="button" class="btn btn-primary" onclick="saveUserProfile()">Save Changes</button>
              <button type="button" class="btn btn-secondary" onclick="closeEditForm()">Cancel</button>
            </div>
          </form>
        </div>
      </div>

    </div> <!-- END .main-content (fixed missing closing tag) -->

  <!-- JS: load Bootstrap BEFORE your main.js so bootstrap.Modal is available -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js/main.js"></script>

  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      const sidebar = document.querySelector('.sidebar');
      const button = document.querySelector('.toggle-btn');

      if (window.innerWidth <= 768 &&
          sidebar.classList.contains('active') &&
          !sidebar.contains(e.target) &&
          !button.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });
    
    function openPostProjectModal() {
      const modal = new bootstrap.Modal(document.getElementById('postProjectModal'));
      modal.show();
    }

  </script>

</body>
</html>