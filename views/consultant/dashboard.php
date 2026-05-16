<style>
  :root { --sidebar-w: 260px; }
  body { background: #0a0f1e; color: #e2e8f0; font-family: 'Inter', sans-serif; }
  .sidebar { width: var(--sidebar-w); background: rgba(255,255,255,0.04); border-right: 1px solid rgba(255,255,255,0.08); position: fixed; top: 0; left: 0; height: 100vh; display: flex; flex-direction: column; z-index: 40; padding-top: 4rem; overflow-y: auto; }
  .sidebar a { display: flex; align-items: center; gap: 10px; padding: 11px 20px; color: rgba(255,255,255,0.55); font-size: 0.875rem; font-weight: 600; text-decoration: none; border-radius: 10px; margin: 2px 10px; transition: all 0.18s; }
  .sidebar a:hover, .sidebar a.active { background: rgba(37,99,235,0.18); color: #fff; }
  .sidebar a.active { border-left: 3px solid #2563eb; }
  .main-area { margin-left: var(--sidebar-w); padding: 5rem 1.5rem 2rem; min-height: 100vh; }
  .glass-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.14), transparent 65%); pointer-events: none; }
  .dash-input { width: 100%; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; padding: 10px 14px; color: #fff; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
  .dash-input:focus { border-color: rgba(37,99,235,0.5); }
  .dash-input option { background: #1e293b; color: #fff; }
  @media (max-width: 768px) {
    .sidebar { width: 100%; height: auto; position: relative; padding-top: 1rem; flex-direction: row; flex-wrap: wrap; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .main-area { margin-left: 0; }
  }
</style>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="px-4 mb-4">
    <p class="text-white/30 text-xs font-bold uppercase tracking-widest mb-1">Consultant</p>
    <p class="text-white font-black text-base"><?= \ConsultMee\Core\View::escape($_SESSION['consultant']['name'] ?? 'Consultant') ?></p>
    <p class="text-white/40 text-xs">@<?= \ConsultMee\Core\View::escape($_SESSION['consultant']['username'] ?? '') ?></p>
  </div>
  <nav>
    <a href="#appointments" onclick="showSection('appointments')"><i class="fa-solid fa-calendar-check fa-fw"></i> Appointments</a>
    <a href="#requests" onclick="showSection('requests')"><i class="fa-solid fa-inbox fa-fw"></i> Requests</a>
    <a href="#history" onclick="showSection('history')"><i class="fa-solid fa-clock-rotate-left fa-fw"></i> History</a>
    <a href="#consultant_projects" onclick="showSection('consultant_projects')"><i class="fa-solid fa-folder-open fa-fw"></i> Browse Projects</a>
    <a href="#profile" onclick="showSection('profile')"><i class="fa-solid fa-user-circle fa-fw"></i> My Profile</a>
  </nav>
  <div class="mt-auto px-4 pb-6">
    <form method="post" action="/consultant/logout">
      <?= csrf_field() ?>
      <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 text-sm font-semibold transition-colors">
        <i class="fa-solid fa-right-from-bracket fa-fw"></i> Logout
      </button>
    </form>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-area">
  <div class="mb-6">
    <h1 id="page-title" class="text-2xl font-black tracking-tight">Appointments</h1>
    <p class="text-white/40 text-sm">Consultant Dashboard</p>
  </div>

  <!-- CONFIRMED APPOINTMENTS -->
  <div id="appointments" style="display:block;">
    <div class="glass-card p-6">
      <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
        <i class="bi bi-calendar-check-fill text-cobalt"></i> Confirmed Appointments
      </h2>
      <div id="appointments_content">
        <p class="text-white/40 text-sm">Loading…</p>
      </div>
    </div>
  </div>

  <!-- APPOINTMENT REQUESTS -->
  <div id="requests" style="display:none;">
    <div class="glass-card p-6">
      <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
        <i class="bi bi-inbox-fill text-cobalt"></i> Appointment Requests
      </h2>
      <div id="appointments_list">
        <p class="text-white/40 text-sm">Loading…</p>
      </div>
    </div>
  </div>

  <!-- HISTORY -->
  <div id="history" style="display:none;">
    <p class="text-white/40 text-sm">Loading…</p>
  </div>

  <!-- BROWSE PROJECTS -->
  <div id="consultant_projects" style="display:none;">
    <div class="glass-card p-6 mb-5">
      <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
        <i class="bi bi-folder2-open text-cobalt"></i> Browse Projects
      </h2>
      <div class="mb-4 flex gap-3 flex-wrap">
        <input type="text" id="consultantProjectSearch" class="dash-input" style="max-width:320px;" placeholder="Search projects…">
        <button onclick="showConsultantProjects(document.getElementById('consultantProjectSearch').value.trim(), 1)"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cobalt to-accent text-white font-bold text-sm hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/20">
          <i class="bi bi-search me-1"></i> Search
        </button>
      </div>
      <div id="consultantProjectsContainer">
        <p class="text-white/40 text-sm">Loading projects…</p>
      </div>
      <div id="consultantProjectsPagination" class="mt-4 flex items-center gap-2 text-sm text-white/50"></div>
    </div>
  </div>

  <!-- PROFILE -->
  <div id="profile" style="display:none;">
    <div class="glass-card p-6">
      <div id="profile_card_container">
        <p class="text-white/40 text-sm">Loading profile…</p>
      </div>
    </div>
  </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 overflow-hidden"
         style="background:rgba(255,255,255,0.94); backdrop-filter:blur(14px); box-shadow:0 28px 85px rgba(15,23,42,0.22);">

      <div class="modal-header border-0 px-4 py-4"
           style="background:linear-gradient(135deg,rgba(37,99,235,0.14) 0%,rgba(14,165,233,0.10) 40%,rgba(255,255,255,0.95) 100%); border-bottom:1px solid rgba(15,23,42,0.08);">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center rounded-4"
               style="width:48px;height:48px;background:linear-gradient(135deg,#0d6efd,#2563eb,#0ea5e9);box-shadow:0 16px 40px rgba(37,99,235,0.25);color:white;font-size:18px;">
            <i class="bi bi-person-badge"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold mb-0" id="editProfileModalLabel" style="color:#0f172a;letter-spacing:-0.4px;">Edit Profile</h5>
            <small class="text-muted fw-semibold">Update your consultant information</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-4 pt-4 pb-3">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-person-fill text-primary me-1"></i>Full Name</label>
            <input type="text" id="profile-name" class="form-control rounded-4" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-telephone-fill text-primary me-1"></i>Phone</label>
            <input type="tel" id="profile-phone" class="form-control rounded-4" maxlength="10" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-briefcase-fill text-primary me-1"></i>Area of Expertise</label>
            <input type="text" id="profile-expertise" class="form-control rounded-4" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-currency-rupee text-primary me-1"></i>Hourly Rate (₹)</label>
            <input type="number" id="profile-rate" class="form-control rounded-4" min="0" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-graph-up-arrow text-primary me-1"></i>Experience</label>
            <input type="text" id="profile-experience" class="form-control rounded-4" placeholder="e.g. 5 years" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-geo-alt-fill text-primary me-1"></i>State</label>
            <select id="profile-state" class="form-select rounded-4" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
              <?php
              $states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
              foreach ($states as $s): ?>
                <option value="<?= \ConsultMee\Core\View::escape($s) ?>"><?= \ConsultMee\Core\View::escape($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-person-workspace text-primary me-1"></i>Identity</label>
            <select id="profile-identity" class="form-select rounded-4" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
              <option value="">Select identity</option>
              <option value="Chartered Accountant">Chartered Accountant</option>
              <option value="Lawyer">Lawyer</option>
              <option value="Doctor">Doctor</option>
              <option value="Financial Advisor">Financial Advisor</option>
              <option value="Business Consultant">Business Consultant</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-image-fill text-primary me-1"></i>Profile Photo</label>
            <input type="file" id="profile-image" accept="image/jpeg,image/png,image/webp" class="form-control rounded-4" style="padding:0.75rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small" style="color:#0f172a;"><i class="bi bi-card-text text-primary me-1"></i>Bio</label>
            <textarea id="profile-bio" class="form-control rounded-4" rows="3" style="padding:0.85rem 1rem;font-weight:700;border:1px solid rgba(15,23,42,0.14);resize:none;"></textarea>
          </div>
          <div class="col-12" id="uploadProgressContainer" style="display:none;">
            <label class="form-label fw-bold small" style="color:#0f172a;">Upload Progress</label>
            <div class="progress rounded-pill" style="height:10px;background:rgba(15,23,42,0.08);">
              <div id="uploadProgressBar" class="progress-bar rounded-pill" role="progressbar" style="width:0%;background:linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);"></div>
            </div>
            <small id="uploadProgressText" class="text-muted fw-semibold mt-1 d-block">Uploading… 0%</small>
          </div>
        </div>
      </div>

      <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2">
        <button type="button" class="btn rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal"
                style="background:rgba(15,23,42,0.06);border:1px solid rgba(15,23,42,0.16);color:#0f172a;">
          <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" onclick="saveProfile()"
                style="background:linear-gradient(90deg,#0d6efd,#2563eb,#0ea5e9);border:none;box-shadow:0 18px 45px rgba(37,99,235,0.25);">
          <i class="bi bi-check-circle me-1"></i> Save Changes
        </button>
      </div>

    </div>
  </div>
</div>
