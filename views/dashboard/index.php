<?php
$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];
?>
<style>
  * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
  body { background: #0a0f1e; color: white; margin: 0; display: flex; min-height: 100vh; }
  .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 240px; z-index: 100; background: rgba(13,21,48,0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(255,255,255,0.08); display: flex; flex-direction: column; padding: 20px 16px; transition: transform 0.3s ease; }
  .sidebar ul { list-style: none; padding: 0; margin: 0; flex: 1; }
  .sidebar ul a { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 12px; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; margin-bottom: 4px; }
  .sidebar ul a:hover { background: rgba(255,255,255,0.08); color: white; }
  .sidebar ul a.active { background: rgba(37,99,235,0.25); color: white; border: 1px solid rgba(37,99,235,0.4); }
  .sidebar-logo { display: block; margin-bottom: 24px; padding: 0 4px; }
  .sidebar-logo img { height: 40px; width: auto; filter: brightness(1.1); }
  .main-content { margin-left: 240px; flex: 1; padding: 28px; min-height: 100vh; background: #0a0f1e; }
  #page-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 20px; background: linear-gradient(90deg, #2563eb, #0ea5e9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  .toggle-btn { display: none; position: fixed; top: 12px; left: 12px; z-index: 200; background: rgba(37,99,235,0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 8px 14px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
  .glass-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.10); border-radius: 20px; padding: 24px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 30% 0%, rgba(37,99,235,0.12), transparent 70%); pointer-events: none; }
  #consultant-list, #categories, #appointments, #history, #projectsContainer { display: grid; gap: 16px; }
  #consultant-list { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
  #categories { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
  .projects-tabs { display: flex; gap: 8px; }
  .tab-btn { padding: 8px 18px; border-radius: 999px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.6); cursor: pointer; transition: all 0.2s; }
  .tab-btn.active, .tab-btn:hover { background: rgba(37,99,235,0.3); border-color: rgba(37,99,235,0.5); color: white; }
  .create-card { background: rgba(37,99,235,0.12); border: 1.5px dashed rgba(37,99,235,0.4); border-radius: 16px; padding: 16px 20px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; }
  .create-card:hover { background: rgba(37,99,235,0.2); transform: translateY(-2px); }
  .create-card-inner { display: flex; align-items: center; gap: 14px; }
  .create-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center; color: #0ea5e9; font-size: 1.1rem; }
  .create-title { font-weight: 700; font-size: 0.9rem; color: white; }
  .create-sub { font-size: 0.75rem; color: rgba(255,255,255,0.45); }
  #profile .profile-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 32px; }
  #user-profile-img { width: 130px; height: 130px; object-fit: cover; border-radius: 50%; border: 3px solid rgba(37,99,235,0.5); }
  .dash-input, .dash-select, .dash-textarea { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; color: white; padding: 10px 13px; width: 100%; outline: none; transition: border-color 0.2s; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
  .dash-input:focus, .dash-select:focus, .dash-textarea:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.18); }
  .dash-input::placeholder, .dash-textarea::placeholder { color: rgba(255,255,255,0.3); }
  .dash-select option { background: #0d1a3a; color: white; }
  .dash-textarea { resize: vertical; }
  .dash-label { display: block; font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.65); margin-bottom: 5px; }
  .modal-content { background: #0d1530 !important; border: 1px solid rgba(255,255,255,0.12) !important; border-radius: 20px !important; color: white !important; }
  .modal-header { border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
  .modal-footer { border-top: 1px solid rgba(255,255,255,0.08) !important; }
  .modal-title { font-weight: 700 !important; }
  .btn-close { filter: invert(1); }
  .logout-btn { margin-top: auto; display: flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 12px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; text-decoration: none; font-size: 0.88rem; font-weight: 600; transition: all 0.2s; cursor: pointer; }
  .logout-btn:hover { background: rgba(239,68,68,0.25); color: #fca5a5; }
  @media (max-width: 768px) { .toggle-btn { display: block; } .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; padding: 70px 16px 24px; } }
</style>

<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>

<div class="sidebar">
  <a href="/" class="sidebar-logo"><img src="/assets/img/logo_bw.png" alt="ConsultME Logo"></a>
  <ul>
    <li><a href="#consultants" onclick="showSection('consultants')" class="active"><i class="fa fa-user-tie"></i> Consultants</a></li>
    <li><a href="#categories" onclick="showSection('categories')"><i class="fa fa-list"></i> Categories</a></li>
    <li><a href="#appointments" onclick="showSection('appointments')"><i class="fa fa-calendar-alt"></i> Appointments</a></li>
    <li><a href="#history" onclick="showSection('history')"><i class="fa fa-history"></i> History</a></li>
    <li><a href="#projects" onclick="showSection('projects')"><i class="fa fa-folder-open"></i> Projects</a></li>
    <li><a href="#profile" onclick="showSection('profile')"><i class="fa fa-user"></i> Profile</a></li>
  </ul>
  <form method="post" action="/logout">
    <?= csrf_field() ?>
    <button type="submit" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Logout</button>
  </form>
</div>

<div class="main-content">
  <h2 id="page-title">Consultant List</h2>

  <div id="consultants"><div id="consultant-list"></div></div>
  <div id="categories" style="display:none;"></div>
  <div id="appointments" style="display:none;"></div>
  <div id="history" style="display:none;"></div>

  <div id="projects" style="display:none;" class="projects-area">
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px;">
      <div class="create-card" onclick="openPostProjectModal()" role="button">
        <div class="create-card-inner">
          <span class="create-icon"><i class="fa-solid fa-plus"></i></span>
          <div>
            <div class="create-title">Create a New Project</div>
            <div class="create-sub">Click to add project details</div>
          </div>
        </div>
      </div>
      <div class="projects-tabs">
        <button id="projectsTabActive" class="tab-btn active" onclick="loadProjects('active',1)">Active Projects</button>
        <button id="projectsTabHistory" class="tab-btn" onclick="loadProjects('history',1)">Project History</button>
      </div>
    </div>
    <div id="projectsContainer" style="display:grid; gap:16px; grid-template-columns:repeat(auto-fill,minmax(300px,1fr));"></div>
    <div id="projectsPagination" style="margin-top:16px;"></div>
  </div>

  <div id="profile" style="display:none;">
    <div class="profile-card">
      <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:flex-start;">
        <div style="text-align:center; flex-shrink:0;">
          <img id="user-profile-img"
               src="<?= UPLOAD_URL_USERS . \ConsultMee\Core\View::escape($user['profile_img'] ?? 'default.png') ?>"
               alt="Profile Image">
        </div>
        <div style="flex:1; min-width:200px;">
          <h3 id="user-name" style="font-size:1.4rem; font-weight:800; margin-bottom:8px;">
            <?= \ConsultMee\Core\View::escape($user['full_name'] ?? '') ?>
          </h3>
          <p style="color:rgba(255,255,255,0.5); margin-bottom:6px; font-size:0.9rem;">
            <i class="fa fa-map-marker-alt" style="color:#0ea5e9; margin-right:6px;"></i>
            <span id="user-state"><?= \ConsultMee\Core\View::escape($user['state'] ?? '') ?></span>
          </p>
          <p style="margin-bottom:4px; font-size:0.9rem;"><span style="color:rgba(255,255,255,0.45);">Username:</span> <span id="user-username"><?= \ConsultMee\Core\View::escape($user['username'] ?? '') ?></span></p>
          <p style="margin-bottom:4px; font-size:0.9rem;"><span style="color:rgba(255,255,255,0.45);">Identity:</span> <span id="user-identity"><?= \ConsultMee\Core\View::escape($user['identity'] ?? '') ?></span></p>
          <p style="margin-bottom:4px; font-size:0.9rem;"><span style="color:rgba(255,255,255,0.45);">Phone:</span> <span id="user-phone"><?= \ConsultMee\Core\View::escape($user['phone'] ?? '') ?></span></p>
          <p style="margin-bottom:4px; font-size:0.9rem;"><span style="color:rgba(255,255,255,0.45);">Email:</span> <span id="user-email"><?= \ConsultMee\Core\View::escape($user['email'] ?? '') ?></span></p>
          <p style="margin-bottom:0; font-size:0.9rem;">
            <span style="color:rgba(255,255,255,0.45);">Interests:</span>
            <span id="user-interest1"><?= \ConsultMee\Core\View::escape($user['interest1'] ?? '') ?></span>,
            <span id="user-interest2"><?= \ConsultMee\Core\View::escape($user['interest2'] ?? '') ?></span>,
            <span id="user-interest3"><?= \ConsultMee\Core\View::escape($user['interest3'] ?? '') ?></span>
          </p>
        </div>
        <div>
          <button type="button" onclick="openEditForm()"
            style="padding:10px 22px; border-radius:12px; background:linear-gradient(90deg,#2563eb,#0ea5e9); color:white; font-weight:600; border:none; cursor:pointer; transition:0.2s;">
            Edit Profile
          </button>
        </div>
      </div>
    </div>

    <div id="edit-profile-form" class="glass-card" style="display:none; margin-top:16px;">
      <h4 style="font-weight:700; margin-bottom:16px;">Edit Profile</h4>
      <form method="POST" enctype="multipart/form-data">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div>
            <label class="dash-label">Full Name *</label>
            <input type="text" name="full_name" id="edit-fullname" value="<?= \ConsultMee\Core\View::escape($user['full_name'] ?? '') ?>" class="dash-input" required>
          </div>
          <div>
            <label class="dash-label">Phone *</label>
            <input type="text" name="phone" id="edit-phone" value="<?= \ConsultMee\Core\View::escape($user['phone'] ?? '') ?>" class="dash-input" required>
          </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
          <div>
            <label class="dash-label">Identity *</label>
            <select name="identity" id="edit-identity" class="dash-select" required>
              <option>Enterprise</option><option>MSME</option><option>Professional</option>
              <option>Self-Employed</option><option>Individual</option>
            </select>
            <script>document.getElementById("edit-identity").value = "<?= \ConsultMee\Core\View::escape($user['identity'] ?? '') ?>";</script>
          </div>
          <div>
            <label class="dash-label">State *</label>
            <select name="state" id="edit-state" class="dash-select" required>
              <option value="">--Select State--</option>
              <?php foreach ($states as $s): ?>
                <option value="<?= \ConsultMee\Core\View::escape($s) ?>" <?= ($user['state'] ?? '') === $s ? 'selected' : '' ?>><?= \ConsultMee\Core\View::escape($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:12px;">
          <?php foreach ([1,2,3] as $n): ?>
          <div>
            <label class="dash-label">Interest <?= $n ?></label>
            <select name="interest<?= $n ?>" id="edit-interest<?= $n ?>" class="dash-select" <?= $n === 1 ? 'required' : '' ?>>
              <option value="">Select Industry</option>
              <?php foreach ($industries as $ind): ?>
                <option value="<?= \ConsultMee\Core\View::escape($ind) ?>" <?= ($user["interest$n"] ?? '') === $ind ? 'selected' : '' ?>><?= \ConsultMee\Core\View::escape($ind) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endforeach; ?>
        </div>
        <div style="margin-bottom:12px;">
          <label class="dash-label">Profile Image</label>
          <input type="file" name="profile_image" id="edit-profile-image" class="dash-input" accept="image/*">
        </div>
        <div id="userUploadProgress" style="display:none; margin-bottom:10px;">
          <div style="width:100%; background:rgba(255,255,255,0.1); border-radius:6px; overflow:hidden;">
            <div id="userUploadBar" style="width:0%; height:8px; background:linear-gradient(90deg,#2563eb,#0ea5e9);"></div>
          </div>
          <small id="userUploadText" style="color:rgba(255,255,255,0.5);">Uploading... 0%</small>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:16px;">
          <button type="button" onclick="saveUserProfile()"
            style="padding:10px 22px; border-radius:12px; background:linear-gradient(90deg,#2563eb,#0ea5e9); color:white; font-weight:600; border:none; cursor:pointer;">
            Save Changes
          </button>
          <button type="button" onclick="closeEditForm()"
            style="padding:10px 22px; border-radius:12px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); font-weight:600; cursor:pointer;">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Post Project Modal -->
<div class="modal fade" id="postProjectModal" tabindex="-1" aria-labelledby="postProjectLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="postProjectLabel">Post New Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="postProjectForm" enctype="multipart/form-data">
        <div class="modal-body" style="display:flex; flex-direction:column; gap:12px;">
          <div><label class="dash-label">Title</label><input type="text" name="title" required class="dash-input"></div>
          <div><label class="dash-label">Short Description</label><input type="text" name="short_description" class="dash-input" maxlength="512"></div>
          <div><label class="dash-label">Detailed Description</label><textarea name="description" class="dash-textarea" rows="4" required></textarea></div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div><label class="dash-label">Budget</label><input type="text" name="budget" class="dash-input"></div>
            <div>
              <label class="dash-label">Billing Cycle</label>
              <select name="billing" class="dash-select">
                <option>--Select--</option><option>Hourly</option><option>Daily</option><option>Weekly</option><option>Monthly</option><option>Quarterly</option><option>Yearly</option>
              </select>
            </div>
          </div>
          <div><label class="dash-label">End Date</label><input type="date" name="expiry_date" class="dash-input"></div>
          <div><label class="dash-label">External File Links</label><input type="text" name="links" class="dash-input" placeholder="https://..."></div>
        </div>
        <div class="modal-footer">
          <button type="button" style="padding:9px 20px; border-radius:10px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); font-weight:600; cursor:pointer;" data-bs-dismiss="modal">Close</button>
          <button type="submit" style="padding:9px 20px; border-radius:10px; background:linear-gradient(90deg,#2563eb,#0ea5e9); color:white; font-weight:600; border:none; cursor:pointer;">Post Project</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function toggleSidebar() { document.querySelector('.sidebar').classList.toggle('active'); }
  document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.sidebar');
    const button = document.querySelector('.toggle-btn');
    if (window.innerWidth <= 768 && sidebar.classList.contains('active') &&
        !sidebar.contains(e.target) && !button.contains(e.target)) {
      sidebar.classList.remove('active');
    }
  });
  function openPostProjectModal() {
    const modal = new bootstrap.Modal(document.getElementById('postProjectModal'));
    modal.show();
  }
</script>
