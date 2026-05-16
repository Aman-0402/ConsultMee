<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Consultant Dashboard | ConsultME</title>

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

  <!-- Bootstrap Icons (used by cd.js in innerHTML) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Font Awesome (sidebar icons used by cd.js querySelector) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">

  <!-- Bootstrap JS (cd.js uses bootstrap.Modal) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }

    body {
      margin: 0;
      display: flex;
      min-height: 100vh;
      background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%);
      color: white;
      overflow-x: hidden;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 240px;
      background: rgba(8, 13, 28, 0.95);
      backdrop-filter: blur(20px);
      border-right: 1px solid rgba(255,255,255,0.08);
      padding-top: 0;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      transition: left 0.3s ease;
      z-index: 1000;
      display: flex;
      flex-direction: column;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      color: rgba(255,255,255,0.65);
      padding: 12px 20px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.2s;
      border-left: 3px solid transparent;
    }

    .sidebar a .fa, .sidebar a i {
      width: 18px;
      text-align: center;
      font-size: 1rem;
    }

    .sidebar a:hover {
      color: white;
      background: rgba(37,99,235,0.15);
      border-left-color: #2563eb;
    }

    .sidebar a.active {
      color: white;
      background: rgba(37,99,235,0.2);
      border-left-color: #2563eb;
    }

    .sidebar a.text-danger {
      color: rgba(248, 113, 113, 0.8) !important;
    }
    .sidebar a.text-danger:hover {
      color: #f87171 !important;
      background: rgba(239,68,68,0.1);
      border-left-color: #ef4444;
    }

    /* ===== TOGGLE BUTTON ===== */
    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
      padding: 10px 15px;
      background: rgba(37,99,235,0.9);
      backdrop-filter: blur(10px);
      color: white;
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 700;
      cursor: pointer;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: 240px;
      padding: 2rem;
      width: 100%;
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar { left: -240px; }
      .sidebar.active { left: 0; }
      .main-content { margin-left: 0; padding: 1rem; }
      .toggle-btn { display: flex; align-items: center; gap: 6px; }
    }

    /* ===== BOOTSTRAP MODAL DARK OVERRIDE ===== */
    /* cd.js dynamically creates modals via insertAdjacentHTML */
    .modal-content {
      background: rgba(10, 15, 30, 0.97) !important;
      border: 1px solid rgba(255,255,255,0.12) !important;
      border-radius: 20px !important;
      backdrop-filter: blur(20px) !important;
      color: white !important;
    }

    .modal-header {
      background: linear-gradient(135deg, rgba(37,99,235,0.15), rgba(14,165,233,0.08), rgba(10,15,30,0.97)) !important;
      border-bottom: 1px solid rgba(255,255,255,0.1) !important;
      color: white !important;
    }

    .modal-title { color: white !important; }

    .modal-footer {
      background: rgba(255,255,255,0.02) !important;
      border-top: 1px solid rgba(255,255,255,0.08) !important;
    }

    .modal-body { background: transparent !important; }

    /* Bootstrap modal backdrop */
    .modal-backdrop { backdrop-filter: blur(4px); }

    /* Form controls inside modals */
    .modal .form-control,
    .modal .form-select,
    .modal select,
    .modal input[type="text"],
    .modal input[type="date"],
    .modal input[type="time"],
    .modal input[type="url"],
    .modal input[type="file"],
    .modal textarea {
      background: rgba(255,255,255,0.08) !important;
      border: 1px solid rgba(255,255,255,0.15) !important;
      border-radius: 12px !important;
      color: white !important;
      padding: 0.75rem 1rem !important;
      font-weight: 600 !important;
    }

    .modal .form-control::placeholder,
    .modal textarea::placeholder { color: rgba(255,255,255,0.3) !important; }

    .modal .form-control:focus,
    .modal .form-select:focus,
    .modal select:focus,
    .modal input:focus,
    .modal textarea:focus {
      border-color: #2563eb !important;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.25) !important;
      background: rgba(255,255,255,0.1) !important;
    }

    .modal select option { background: #0d1a3a; color: white; }

    .modal label,
    .modal .form-label { color: rgba(255,255,255,0.8) !important; font-weight: 700 !important; }

    /* cd.js renders Bootstrap-styled cards with light bg — keep them as-is (they have inline styles) */

    /* Progress bar */
    #uploadProgressContainer { color: rgba(255,255,255,0.7); }

    /* Scrollbar for main area */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }

    /* Alert overrides */
    .alert-info { background: rgba(14,165,233,0.15) !important; border: 1px solid rgba(14,165,233,0.3) !important; color: #7dd3fc !important; border-radius: 12px !important; }
    .alert-danger { background: rgba(239,68,68,0.15) !important; border: 1px solid rgba(239,68,68,0.3) !important; color: #fca5a5 !important; border-radius: 12px !important; }
    .alert-warning { background: rgba(245,158,11,0.15) !important; border: 1px solid rgba(245,158,11,0.3) !important; color: #fcd34d !important; border-radius: 12px !important; }

    /* Table override for history section */
    .table { color: rgba(255,255,255,0.85) !important; }
    .table-hover tbody tr:hover { background: rgba(37,99,235,0.1) !important; }
    .table thead th { background: rgba(255,255,255,0.05) !important; color: rgba(255,255,255,0.7) !important; border-color: rgba(255,255,255,0.1) !important; }
    .table tbody td { border-color: rgba(255,255,255,0.06) !important; }
  </style>
</head>
<body>

  <!-- Mobile Toggle -->
  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fa fa-bars"></i> Menu
  </button>

  <!-- ===== SIDEBAR ===== -->
  <div class="sidebar">

    <!-- Logo -->
    <div class="px-4 py-5 border-b border-white/10 mb-2">
      <a href="https://consultmee.in">
        <img src="./assets/img/logo.png" alt="ConsultME" class="h-8 w-auto">
      </a>
      <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-semibold">
        <i class="bi bi-briefcase-fill text-xs"></i> Consultant
      </div>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 py-2">
      <a href="#profile" class="active" onclick="showSection('profile')">
        <i class="fa fa-home"></i> Dashboard
      </a>
      <a href="#appointments" onclick="showSection('appointments')">
        <i class="fa fa-calendar-check"></i> Appointments
      </a>
      <a href="#requests" onclick="showSection('requests')">
        <i class="fa fa-inbox"></i> Requests
      </a>
      <a href="#consultant_projects" onclick="showSection('consultant_projects')">
        <i class="fa fa-folder-open"></i> Browse Projects
      </a>
      <a href="#history" onclick="showSection('history')">
        <i class="fa fa-history"></i> History
      </a>
    </nav>

    <!-- Logout -->
    <div class="pb-4 border-t border-white/10 pt-2">
      <a href="logout.php" class="text-danger">
        <i class="fa fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>

  <!-- ===== MAIN CONTENT ===== -->
  <div class="main-content">

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 id="page-title" class="text-xl font-black text-white tracking-tight">Dashboard</h1>
        <p class="text-white/40 text-sm mt-0.5">ConsultME Consultant Portal</p>
      </div>
    </div>

    <!-- Profile Section -->
    <div id="profile" style="display:none;">
      <div id="profile_card_container"></div>
    </div>

    <!-- Appointments (Confirmed) Section -->
    <div id="appointments" style="display:none;">
      <div id="appointments_content"></div>
    </div>

    <!-- Requests Section -->
    <div id="requests" style="display:none;">
      <div id="appointments_list"></div>
    </div>

    <!-- Browse Projects Section -->
    <div id="consultant_projects" style="display:none;">

      <!-- Search bar -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5">
        <div class="flex items-center gap-2 w-full sm:w-auto">
          <div class="relative flex-1 sm:w-80">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
            <input
              id="consultantProjectSearch"
              type="text"
              placeholder="Search projects by keyword"
              class="w-full bg-white/8 border border-white/12 rounded-xl text-white text-sm pl-9 pr-4 py-2.5 outline-none focus:border-cobalt focus:ring-2 focus:ring-cobalt/20 placeholder-white/30"
            >
          </div>
          <button
            id="consultantSearchBtn"
            class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cobalt to-accent text-white text-sm font-semibold hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/20"
          >
            Search
          </button>
          <button
            id="consultantClearBtn"
            class="px-4 py-2.5 rounded-xl bg-white/8 border border-white/12 text-white/70 text-sm font-semibold hover:bg-white/12 transition-all"
          >
            Clear
          </button>
        </div>
        <span class="text-white/35 text-xs">Showing active projects (open & not expired)</span>
      </div>

      <div id="consultantProjectsContainer"></div>
      <div id="consultantProjectsPagination" class="mt-4"></div>
    </div>

    <!-- History Section -->
    <div id="history" style="display:none;"></div>

  </div>

  <!-- ===== EDIT PROFILE MODAL (Bootstrap — cd.js uses bootstrap.Modal) ===== -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0 px-5 py-4">
          <div class="d-flex align-items-center gap-3">
            <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#2563eb,#0ea5e9);display:flex;align-items:center;justify-content:center;color:white;font-size:18px;box-shadow:0 16px 40px rgba(37,99,235,0.3);">
              <i class="bi bi-person-badge"></i>
            </div>
            <div>
              <h5 class="modal-title mb-0" id="editProfileModalLabel" style="color:white;font-weight:900;letter-spacing:-0.4px;">Edit Profile</h5>
              <small style="color:rgba(255,255,255,0.45);font-weight:600;">Update your consultant profile details</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body px-5 py-4">
          <form id="editProfileForm">
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" id="profile-name" class="form-control" placeholder="Your name">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" id="profile-phone" class="form-control" placeholder="10-digit number">
              </div>

              <div class="col-md-6">
                <label class="form-label">Area of Expertise</label>
                <input type="text" id="profile-expertise" class="form-control" placeholder="e.g. Finance, AI">
              </div>
              <div class="col-md-6">
                <label class="form-label">Hourly Rate (Rs.)</label>
                <input type="text" id="profile-rate" class="form-control" placeholder="e.g. 500">
              </div>

              <div class="col-md-6">
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
              <div class="col-md-6">
                <label class="form-label">State</label>
                <select id="profile-state" name="state" class="form-control">
                  <option value="">--Select State--</option>
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
              </div>

              <div class="col-12">
                <label class="form-label">Bio</label>
                <input type="text" id="profile-bio" class="form-control" placeholder="Short bio (max 20 words)">
              </div>

              <div class="col-12">
                <label class="form-label">Experience</label>
                <textarea id="profile-experience" class="form-control" rows="3" placeholder="Describe your experience..."></textarea>
              </div>

              <div class="col-12">
                <label class="form-label">Profile Image</label>
                <input type="file" id="profile-image" class="form-control" accept="image/*">
              </div>

            </div>
          </form>

          <!-- Upload progress -->
          <div id="uploadProgressContainer" style="display:none;margin-top:14px;">
            <div style="width:100%;background:rgba(255,255,255,0.1);border-radius:8px;overflow:hidden;">
              <div id="uploadProgressBar" style="width:0%;height:8px;background:linear-gradient(90deg,#2563eb,#0ea5e9);transition:width 0.2s;"></div>
            </div>
            <small id="uploadProgressText" class="text-white/50 mt-1 block">Uploading... 0%</small>
          </div>
        </div>

        <div class="modal-footer px-5 pb-4 pt-2">
          <button type="button" class="btn rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal"
                  style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:white;">
            Cancel
          </button>
          <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-bold"
                  onclick="saveProfile()"
                  style="background:linear-gradient(90deg,#2563eb,#0ea5e9);border:none;box-shadow:0 16px 40px rgba(37,99,235,0.3);">
            <i class="bi bi-check-circle me-2"></i>Save Changes
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- cd.js -->
  <script src="./js/cd.js"></script>

  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('active');
    }

    document.addEventListener('click', function(e) {
      const sidebar = document.querySelector('.sidebar');
      const button = document.querySelector('.toggle-btn');
      if (window.innerWidth <= 768 &&
          sidebar.classList.contains('active') &&
          !sidebar.contains(e.target) &&
          !button.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });

    (function setupConsultantProjectsHooks() {
      const link = document.querySelector('.sidebar a[href="#consultant_projects"]');
      if (link) {
        link.addEventListener('click', function() {
          setTimeout(() => {
            if (typeof showConsultantProjects === 'function') showConsultantProjects('', 1);
          }, 120);
        });
      }

      const searchBtn = document.getElementById('consultantSearchBtn');
      const clearBtn = document.getElementById('consultantClearBtn');
      if (searchBtn) {
        searchBtn.addEventListener('click', function() {
          const q = (document.getElementById('consultantProjectSearch') || {value: ''}).value.trim();
          if (typeof showConsultantProjects === 'function') showConsultantProjects(q, 1);
        });
      }
      if (clearBtn) {
        clearBtn.addEventListener('click', function() {
          const input = document.getElementById('consultantProjectSearch');
          if (input) input.value = '';
          if (typeof showConsultantProjects === 'function') showConsultantProjects('', 1);
        });
      }

      const searchInput = document.getElementById('consultantProjectSearch');
      if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            const q = searchInput.value.trim();
            if (typeof showConsultantProjects === 'function') showConsultantProjects(q, 1);
          }
        });
      }
    })();
  </script>

</body>
</html>
