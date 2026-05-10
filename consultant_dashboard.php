<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">

  <!-- Latest Bootstrap (kept as in your original file) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (for sidebar icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <title>Consultant Dashboard</title>
  <link rel="stylesheet" href="./CSS/dashboard.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      margin: 0;
      display: flex;
      min-height: 100vh;
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }
    .sidebar {
      width: 240px;
      background-color: #343a40;
      color: white;
      padding-top: 1rem;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      transition: left 0.3s ease;
      z-index: 1000;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      font-size: 16px;
    }

    .sidebar a .fa {
      width: 18px;
      text-align: center;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #0d6efd;
    }

    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
      padding: 10px 15px;
      background-color: #0d6efd;
      color: white;
      border: none;
      border-radius: 5px;
    }

    .main-content {
      margin-left: 240px;
      padding: 2rem;
      width: 100%;
      transition: margin-left 0.3s ease;
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -240px;
      }

      .sidebar.active {
        left: 0;
      }

      .main-content {
        margin-left: 0;
        padding: 1rem;
      }

      .toggle-btn {
        display: block;
      }
    }
    /* ===============================
       CONSULTME PREMIUM EDIT PROFILE MODAL
    ================================ */
    
    .edit-profile-modal-content {
      border-radius: 22px;
      border: 1px solid rgba(15, 23, 42, 0.10);
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(14px);
      box-shadow: 0 28px 90px rgba(15, 23, 42, 0.20);
      overflow: hidden;
      position: relative;
    }
    
    /* Header */
    .edit-profile-modal-header {
      border: 0;
      padding: 18px 22px;
      background: linear-gradient(135deg,
        rgba(37, 99, 235, 0.14) 0%,
        rgba(14, 165, 233, 0.10) 40%,
        rgba(255, 255, 255, 0.95) 100%
      );
      border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }
    
    .edit-profile-modal-icon {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9);
      color: white;
      font-size: 18px;
      box-shadow: 0 18px 45px rgba(37, 99, 235, 0.22);
    }
    
    .edit-profile-modal-title {
      font-weight: 950;
      color: #0f172a;
      margin: 0;
      letter-spacing: -0.4px;
    }
    
    .edit-profile-modal-subtitle {
      font-weight: 700;
      color: #64748b;
    }
    
    /* Body */
    .edit-profile-modal-body {
      padding: 22px;
    }
    
    /* Grid layout */
    .edit-profile-form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
    }
    
    .edit-field {
      background: rgba(255, 255, 255, 0.85);
      border: 1px solid rgba(15, 23, 42, 0.10);
      border-radius: 18px;
      padding: 14px 16px;
      box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
      transition: 0.25s ease;
    }
    
    .edit-field:hover {
      transform: translateY(-2px);
      border-color: rgba(37, 99, 235, 0.22);
      box-shadow: 0 20px 55px rgba(15, 23, 42, 0.10);
    }
    
    .edit-field label {
      font-weight: 900;
      font-size: 0.88rem;
      color: #0f172a;
      margin-bottom: 8px;
    }
    
    .edit-field-full {
      grid-column: span 2;
    }
    
    /* Inputs */
    .edit-field .form-control,
    .edit-field select {
      border-radius: 14px !important;
      border: 1px solid rgba(15, 23, 42, 0.14) !important;
      padding: 0.75rem 0.95rem !important;
      font-weight: 750;
      font-size: 0.95rem;
      background: rgba(255, 255, 255, 0.95) !important;
      transition: 0.2s ease;
    }
    
    .edit-field .form-control:focus,
    .edit-field select:focus {
      border-color: rgba(37, 99, 235, 0.55) !important;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
    }
    
    /* Footer */
    .edit-profile-modal-footer {
      border: 0;
      padding: 18px 22px;
      background: rgba(15, 23, 42, 0.02);
      border-top: 1px solid rgba(15, 23, 42, 0.08);
    }
    
    .btn-save-profile-modal {
      background: linear-gradient(90deg, #0d6efd, #2563eb, #0ea5e9) !important;
      border: none !important;
      box-shadow: 0 18px 45px rgba(37, 99, 235, 0.25);
      transition: 0.22s ease;
    }
    
    .btn-save-profile-modal:hover {
      transform: translateY(-2px);
      box-shadow: 0 24px 60px rgba(37, 99, 235, 0.35);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .edit-profile-form-grid {
        grid-template-columns: 1fr;
      }
    
      .edit-field-full {
        grid-column: span 1;
      }
    }

  </style>
</head>
<body>
  <!-- Toggle Sidebar Button -->
    <button class="toggle-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i> Menu</button>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="https://consultmee.in" class="d-block text-center mb-2">
      <img class="text-white" height="70" width="180" src="./assets/img/logo_bw.png" alt="Logo">
    </a>

    <a href="#profile" class="active" onclick="showSection('profile')">
      <i class="fa fa-home me-2"></i> Dashboard
    </a>

    <a href="#appointments" onclick="showSection('appointments')">
      <i class="fa fa-calendar-check me-2"></i> Appointments
    </a>

    <a href="#requests" onclick="showSection('requests')">
      <i class="fa fa-inbox me-2"></i> Requests
    </a>

    <!-- NEW: Browse Projects for consultants -->
    <a href="#consultant_projects" onclick="showSection('consultant_projects')">
      <i class="fa fa-folder-open me-2"></i> Browse Projects
    </a>

    <a href="#history" onclick="showSection('history')">
      <i class="fa fa-history me-2"></i> History
    </a>

    <a href="logout.php" class="text-danger">
      <i class="fa fa-sign-out-alt me-2"></i> Logout
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div id="profile" style="display:none;">
      <!-- Profile content -->
      <div id="profile_card_container" class="container mt-4"></div>
    </div>

    <div id="appointments" style="display:none;">
      <div id="appointments_content"></div>
    </div>

    <div id="requests" style="display:none;">
      <div id="appointments_list"></div>
    </div>

    <!-- NEW: Consultant Projects Section -->
    <div id="consultant_projects" style="display:none;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
          <input id="consultantProjectSearch" class="form-control" style="max-width:360px;" placeholder="Search projects by keyword">
          <button class="btn btn-primary btn-sm" id="consultantSearchBtn">Search</button>
          <button class="btn btn-outline-secondary btn-sm" id="consultantClearBtn">Clear</button>
        </div>
        <div>
          <small class="text-muted">Showing active projects (open & not expired)</small>
        </div>
      </div>

      <div id="consultantProjectsContainer"></div>
      <div id="consultantProjectsPagination" class="mt-3"></div>
    </div>

    <div id="history" style="display:none;">
    </div>
  </div>

  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProfileForm">
            <div class="mb-3"><label class="form-label">Name</label><input type="text" id="profile-name" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Phone</label><input type="text" id="profile-phone" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Expertise</label><input type="text" id="profile-expertise" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Bio</label><input type="text" id="profile-bio" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Hourly Rate(Rs.)</label><input type="text" id="profile-rate" class="form-control"></div>
            <div class="mb-3">
                <label class="form-label">Identity</label>
                <select id="profile-identity" name="identity" class="form-control">
                    <option>--Select--</option>
                    <option>Consultant</option>
                    <option>Trainer</option>
                    <option>Mentor</option>
                    <option>Expert</option>
                    <option>Firm</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">State</label>
              <select id="profile-state" name="state" class="form-control">
                <option value="">--Select State--</option>
                <!-- Indian States -->
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

                <!-- Union Territories -->
                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                <option value="Chandigarh">Chandigarh</option>
                <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                <option value="Delhi">Delhi</option>
                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                <option value="Ladakh">Ladakh</option>
                <option value="Lakshadweep">Lakshadweep</option>
                <option value="Puducherry">Puducherry</option>
              </select>
            </div>
            <div class="mb-3"><label class="form-label">Experience</label><textarea id="profile-experience" class="form-control" rows="3"></textarea></div>
            <div class="mb-3">
              <label class="form-label">Profile Image</label>
              <input type="file" id="profile-image" class="form-control" accept="image/*">
            </div>
          </form>
          <div id="uploadProgressContainer" style="display:none; margin-top:10px;">
              <div style="width:100%; background:#e9ecef; border-radius:6px; overflow:hidden;">
                <div id="uploadProgressBar" style="width:0%; height:8px; background:linear-gradient(90deg,#0d6efd,#00c6ff);"></div>
              </div>
              <small id="uploadProgressText">Uploading... 0%</small>
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="./js/cd.js"></script>
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

    // Hook the new consultant projects UI:
    (function setupConsultantProjectsHooks(){
      // safe guards: functions expected from cd.js: showConsultantProjects
      const link = document.querySelector('.sidebar a[href="#consultant_projects"]');
      if (link) {
        link.addEventListener('click', function () {
          // small delay to let showSection run (if it hides/shows DOM)
          setTimeout(() => {
            if (typeof showConsultantProjects === 'function') {
              showConsultantProjects('', 1);
            }
          }, 120);
        });
      }

      const searchBtn = document.getElementById('consultantSearchBtn');
      const clearBtn = document.getElementById('consultantClearBtn');
      if (searchBtn) {
        searchBtn.addEventListener('click', function () {
          const q = (document.getElementById('consultantProjectSearch') || {value:''}).value.trim();
          if (typeof showConsultantProjects === 'function') showConsultantProjects(q, 1);
        });
      }
      if (clearBtn) {
        clearBtn.addEventListener('click', function () {
          const input = document.getElementById('consultantProjectSearch');
          if (input) input.value = '';
          if (typeof showConsultantProjects === 'function') showConsultantProjects('', 1);
        });
      }
    })();
  </script>
</body>
</html>