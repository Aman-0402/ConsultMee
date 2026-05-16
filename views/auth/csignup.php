<?php
$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];
$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
?>
<style>
  body { margin:0; min-height:100vh; background:#0f2854; font-family:'Inter',system-ui,sans-serif; overflow:hidden; }
  .blob { position:fixed; border-radius:50%; filter:blur(80px); pointer-events:none; z-index:0; }
  .blob-1 { width:500px; height:500px; top:-120px; right:-100px; background:rgba(73,136,196,0.28); }
  .blob-2 { width:400px; height:400px; bottom:-80px; left:-80px; background:rgba(28,77,141,0.38); }
  .back-btn { position:fixed; top:24px; left:24px; z-index:100; display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:10px; border:1px solid rgba(189,232,245,0.25); background:rgba(255,255,255,0.08); color:rgba(189,232,245,0.85); font-size:0.83rem; font-weight:600; text-decoration:none; backdrop-filter:blur(12px); transition:all 0.22s; }
  .back-btn:hover { background:rgba(255,255,255,0.15); color:#bde8f5; transform:translateX(-2px); }

  .page-grid { display:grid; grid-template-columns:1fr 1fr; height:100vh; position:relative; z-index:1; }

  /* LEFT */
  .left-panel { display:flex; flex-direction:column; justify-content:center; padding:80px 56px; }
  .consultant-badge { display:inline-flex; align-items:center; gap:8px; padding:7px 14px; border-radius:20px; background:rgba(73,136,196,0.18); border:1px solid rgba(73,136,196,0.3); color:#bde8f5; font-size:0.78rem; font-weight:700; letter-spacing:0.04em; margin-bottom:22px; }
  .tagline { font-size:2.3rem; font-weight:900; color:#fff; line-height:1.15; letter-spacing:-0.04em; margin-bottom:14px; }
  .tagline span { color:#bde8f5; }
  .sub { color:rgba(189,232,245,0.65); font-size:0.92rem; line-height:1.6; max-width:320px; margin-bottom:32px; }
  .feature-item { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
  .feature-icon { width:34px; height:34px; border-radius:9px; background:rgba(73,136,196,0.22); border:1px solid rgba(73,136,196,0.28); display:flex; align-items:center; justify-content:center; color:#bde8f5; font-size:0.88rem; flex-shrink:0; }
  .feature-text { color:rgba(189,232,245,0.7); font-size:0.84rem; font-weight:500; }
  .stat-row { display:flex; gap:24px; margin-top:28px; padding-top:24px; border-top:1px solid rgba(189,232,245,0.12); }
  .stat { text-align:center; }
  .stat-num { font-size:1.5rem; font-weight:900; color:#bde8f5; }
  .stat-lbl { font-size:0.72rem; font-weight:600; color:rgba(189,232,245,0.5); letter-spacing:0.04em; }

  /* RIGHT */
  .right-panel { display:flex; align-items:center; justify-content:center; padding:40px 40px 40px 20px; }
  .auth-card { width:100%; max-width:430px; background:rgba(255,255,255,0.97); border-radius:24px; padding:32px 34px 26px; box-shadow:0 30px 80px rgba(0,0,0,0.4); display:flex; flex-direction:column; max-height:calc(100vh - 80px); }

  /* PROGRESS */
  .steps-bar { display:flex; align-items:center; margin-bottom:6px; }
  .step-dot { width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:800; flex-shrink:0; transition:all 0.3s; }
  .step-dot.done { background:#0f2854; color:#fff; }
  .step-dot.active { background:linear-gradient(135deg,#1c4d8d,#4988c4); color:#fff; box-shadow:0 4px 14px rgba(28,77,141,0.4); }
  .step-dot.pending { background:#f1f5f9; color:#94a3b8; border:1.5px solid #e2e8f0; }
  .step-line { flex:1; height:2px; transition:background 0.4s; }
  .step-line.done { background:linear-gradient(90deg,#0f2854,#1c4d8d); }
  .step-line.pending { background:#e2e8f0; }
  .step-labels { display:flex; justify-content:space-between; margin-bottom:20px; }
  .step-label { font-size:0.67rem; font-weight:700; color:#94a3b8; letter-spacing:0.03em; text-transform:uppercase; }
  .step-label.active { color:#1c4d8d; }

  /* FORM */
  .step-panel { display:none; flex-direction:column; gap:11px; flex:1; }
  .step-panel.active { display:flex; }
  .field-label { display:block; font-size:0.72rem; font-weight:700; color:#0f2854; margin-bottom:5px; letter-spacing:0.04em; text-transform:uppercase; }
  .field-wrap { position:relative; }
  .field-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#4988c4; font-size:0.88rem; pointer-events:none; }
  .field-icon-top { position:absolute; left:12px; top:13px; color:#4988c4; font-size:0.88rem; pointer-events:none; }
  .form-input { width:100%; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 14px 10px 38px; color:#0f172a; font-size:0.86rem; outline:none; transition:border-color 0.2s,box-shadow 0.2s,background 0.2s; box-sizing:border-box; font-family:'Inter',sans-serif; }
  .form-input:focus { border-color:#1c4d8d; box-shadow:0 0 0 3px rgba(28,77,141,0.1); background:#fff; }
  .form-input::placeholder { color:#94a3b8; }
  select.form-input { appearance:auto; }
  textarea.form-input { padding-top:10px; padding-bottom:10px; resize:none; }
  .field-row { display:grid; grid-template-columns:1fr 1fr; gap:11px; }

  /* BTNS */
  .btn-row { display:flex; gap:10px; margin-top:auto; padding-top:14px; border-top:1px solid #f1f5f9; }
  .btn-prev { flex:1; padding:10px; border-radius:11px; background:#f1f5f9; border:1.5px solid #e2e8f0; color:#475569; font-size:0.875rem; font-weight:700; cursor:pointer; transition:all 0.2s; }
  .btn-prev:hover { background:#e2e8f0; }
  .btn-next { flex:2; padding:10px; border-radius:11px; background:linear-gradient(135deg,#0f2854,#1c4d8d 55%,#4988c4); color:#fff; font-size:0.875rem; font-weight:700; border:none; cursor:pointer; box-shadow:0 4px 18px rgba(15,40,84,0.4); transition:all 0.25s cubic-bezier(0.22,1,0.36,1); }
  .btn-next:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(15,40,84,0.5); filter:brightness(1.08); }
  .btn-next:active { transform:scale(0.98); }
  .eye-btn { position:absolute; right:11px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94a3b8; padding:4px; line-height:1; }
  #messageBox { border-radius:10px; padding:10px 14px; font-size:0.82rem; font-weight:600; text-align:center; margin-bottom:12px; }

  @media(max-width:768px) {
    body { overflow:auto; }
    .page-grid { grid-template-columns:1fr; height:auto; }
    .left-panel { display:none; }
    .right-panel { padding:100px 16px 40px; }
    .auth-card { max-height:none; }
  }
</style>

<a href="/consultant/login" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="page-grid">

  <!-- LEFT PANEL -->
  <div class="left-panel">
    <a href="/"><img src="/assets/img/logo.png" alt="ConsultMee" style="height:40px;width:auto;filter:brightness(0) invert(1);margin-bottom:32px;"></a>
    <div class="consultant-badge"><i class="bi bi-briefcase-fill"></i> CONSULTANT PORTAL</div>
    <div class="tagline">Share your expertise.<br><span>Grow your practice.</span></div>
    <p class="sub">Join ConsultMee's growing network of verified consultants and connect with clients who need your skills.</p>
    <div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-cash-stack"></i></span><span class="feature-text">Set your own hourly rates</span></div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-calendar2-check-fill"></i></span><span class="feature-text">Manage appointments easily</span></div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-star-fill"></i></span><span class="feature-text">Build your reputation with reviews</span></div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-graph-up-arrow"></i></span><span class="feature-text">Grow your client base organically</span></div>
    </div>
    <div class="stat-row">
      <div class="stat"><div class="stat-num">500+</div><div class="stat-lbl">Consultants</div></div>
      <div class="stat"><div class="stat-num">30+</div><div class="stat-lbl">Industries</div></div>
      <div class="stat"><div class="stat-num">₹0</div><div class="stat-lbl">Join Fee</div></div>
    </div>
  </div>

  <!-- RIGHT FORM PANEL -->
  <div class="right-panel">
    <div class="auth-card">

      <!-- PROGRESS -->
      <div>
        <div class="steps-bar">
          <div class="step-dot active" id="dot-1">1</div>
          <div class="step-line pending" id="line-1"></div>
          <div class="step-dot pending" id="dot-2">2</div>
          <div class="step-line pending" id="line-2"></div>
          <div class="step-dot pending" id="dot-3">3</div>
        </div>
        <div class="step-labels">
          <span class="step-label active" id="lbl-1">Basic Info</span>
          <span class="step-label" id="lbl-2">Expertise</span>
          <span class="step-label" id="lbl-3">Profile & Password</span>
        </div>
      </div>

      <div id="messageBox" style="display:none;"></div>

      <form method="post" action="/consultant/signup" id="csignupForm">
        <?= csrf_field() ?>

        <!-- STEP 1: Basic Info -->
        <div class="step-panel active" id="step-1">
          <div style="margin-bottom:2px;">
            <h2 style="font-size:1.25rem;font-weight:900;color:#0f2854;margin:0 0 3px;letter-spacing:-0.02em;">Create consultant account</h2>
            <p style="color:#64748b;font-size:0.8rem;margin:0;">Step 1 of 3 — Your basic information</p>
          </div>
          <div class="field-row">
            <div>
              <label class="field-label">Full Name</label>
              <div class="field-wrap"><i class="bi bi-person-fill field-icon"></i>
                <input type="text" name="full_name" class="form-input" placeholder="John Doe" required>
              </div>
            </div>
            <div>
              <label class="field-label">Username</label>
              <div class="field-wrap"><i class="bi bi-at field-icon"></i>
                <input type="text" name="username" class="form-input" placeholder="johndoe" required>
              </div>
            </div>
          </div>
          <div>
            <label class="field-label">Email Address</label>
            <div class="field-wrap"><i class="bi bi-envelope-fill field-icon"></i>
              <input type="email" name="email" class="form-input" placeholder="your@email.com" required autocomplete="email">
            </div>
          </div>
          <div>
            <label class="field-label">Phone Number</label>
            <div class="field-wrap"><i class="bi bi-telephone-fill field-icon"></i>
              <input type="tel" name="phone" class="form-input" placeholder="10-digit number" maxlength="10" required>
            </div>
          </div>
        </div>

        <!-- STEP 2: Expertise -->
        <div class="step-panel" id="step-2">
          <div style="margin-bottom:2px;">
            <h2 style="font-size:1.25rem;font-weight:900;color:#0f2854;margin:0 0 3px;letter-spacing:-0.02em;">Your expertise</h2>
            <p style="color:#64748b;font-size:0.8rem;margin:0;">Step 2 of 3 — Tell clients about your background</p>
          </div>
          <div>
            <label class="field-label">Area of Expertise</label>
            <div class="field-wrap"><i class="bi bi-briefcase-fill field-icon"></i>
              <select name="industry" class="form-input" required>
                <option value="">Select your expertise</option>
                <?php foreach ($industries as $ind): ?>
                  <option value="<?= htmlspecialchars($ind) ?>"><?= htmlspecialchars($ind) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="field-row">
            <div>
              <label class="field-label">State</label>
              <div class="field-wrap"><i class="bi bi-geo-alt-fill field-icon"></i>
                <select name="State" class="form-input" required>
                  <option value="">Select state</option>
                  <?php foreach ($states as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div>
              <label class="field-label">Experience</label>
              <div class="field-wrap"><i class="bi bi-graph-up-arrow field-icon"></i>
                <input type="text" name="experience" class="form-input" placeholder="e.g. 5 years" required>
              </div>
            </div>
          </div>
          <div>
            <label class="field-label">Professional Identity</label>
            <div class="field-wrap"><i class="bi bi-person-badge-fill field-icon"></i>
              <select name="identity" class="form-input">
                <option value="">Select identity</option>
                <option value="Chartered Accountant">Chartered Accountant</option>
                <option value="Lawyer">Lawyer</option>
                <option value="Doctor">Doctor</option>
                <option value="Financial Advisor">Financial Advisor</option>
                <option value="Business Consultant">Business Consultant</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
        </div>

        <!-- STEP 3: Bio + Password -->
        <div class="step-panel" id="step-3">
          <div style="margin-bottom:2px;">
            <h2 style="font-size:1.25rem;font-weight:900;color:#0f2854;margin:0 0 3px;letter-spacing:-0.02em;">Profile & security</h2>
            <p style="color:#64748b;font-size:0.8rem;margin:0;">Step 3 of 3 — Finish your profile</p>
          </div>
          <div>
            <label class="field-label">Professional Bio</label>
            <div class="field-wrap"><i class="bi bi-card-text field-icon-top"></i>
              <textarea name="bio" rows="3" class="form-input" style="padding-left:38px;" placeholder="Describe your expertise, experience and what clients can expect..." required></textarea>
            </div>
          </div>
          <div>
            <label class="field-label">Password</label>
            <div class="field-wrap"><i class="bi bi-lock-fill field-icon"></i>
              <input type="password" name="password" id="pw1" class="form-input" placeholder="Create a strong password" required>
              <button type="button" class="eye-btn" onclick="togglePw('pw1','eye1')" tabindex="-1"><i id="eye1" class="bi bi-eye" style="font-size:0.88rem;"></i></button>
            </div>
          </div>
          <div id="strength-wrap" style="display:none;">
            <div style="height:4px;border-radius:4px;background:#e2e8f0;overflow:hidden;">
              <div id="strength-bar" style="height:100%;width:0;border-radius:4px;transition:width 0.3s,background 0.3s;"></div>
            </div>
            <p id="strength-label" style="font-size:0.7rem;font-weight:600;color:#94a3b8;margin:3px 0 0;"></p>
          </div>
          <div style="background:#e8f0fb;border:1px solid #bde8f5;border-radius:10px;padding:10px 13px;display:flex;gap:9px;align-items:flex-start;">
            <i class="bi bi-info-circle-fill" style="color:#1c4d8d;margin-top:1px;font-size:0.85rem;flex-shrink:0;"></i>
            <span style="font-size:0.76rem;color:#0f2854;line-height:1.5;">By registering you agree to our <a href="#" style="color:#1c4d8d;font-weight:700;">Terms</a> & <a href="#" style="color:#1c4d8d;font-weight:700;">Privacy Policy</a>. Your profile will be reviewed before going live.</span>
          </div>
        </div>

        <!-- BUTTON ROW -->
        <div class="btn-row">
          <button type="button" id="btn-prev" class="btn-prev" style="display:none;" onclick="prevStep()"><i class="bi bi-arrow-left me-1"></i> Back</button>
          <button type="button" id="btn-next" class="btn-next" onclick="nextStep()">Continue <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </form>

      <p style="text-align:center;margin-top:12px;font-size:0.8rem;color:#94a3b8;">
        Already registered? <a href="/consultant/login" style="color:#1c4d8d;font-weight:700;text-decoration:none;" onmouseover="this.style.color='#4988c4'" onmouseout="this.style.color='#1c4d8d'">Login</a>
        &nbsp;·&nbsp;
        <a href="/signup" style="color:#4988c4;font-weight:600;text-decoration:none;" onmouseover="this.style.color='#1c4d8d'" onmouseout="this.style.color='#4988c4'">Client? Register here</a>
      </p>

    </div>
  </div>
</div>

<script>
let current = 1;
const total = 3;

function showMsg(text, isErr) {
  const b = document.getElementById('messageBox');
  b.style.display = 'block';
  b.textContent = text;
  b.style.cssText = isErr
    ? 'display:block;border-radius:10px;padding:10px 14px;font-size:0.82rem;font-weight:600;text-align:center;margin-bottom:12px;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;'
    : 'display:block;border-radius:10px;padding:10px 14px;font-size:0.82rem;font-weight:600;text-align:center;margin-bottom:12px;color:#0f2854;background:#e8f0fb;border:1px solid #bde8f5;';
}
function hideMsg() { document.getElementById('messageBox').style.display='none'; }

function validateStep(n) {
  const inputs = document.querySelectorAll(`#step-${n} [required]`);
  for (const inp of inputs) {
    if (!inp.value.trim()) { showMsg('Please fill all required fields.', true); inp.focus(); return false; }
  }
  return true;
}

function setStep(n) {
  for (let i = 1; i <= total; i++) {
    document.getElementById(`step-${i}`).classList.toggle('active', i === n);
    const dot = document.getElementById(`dot-${i}`);
    dot.className = 'step-dot ' + (i < n ? 'done' : i === n ? 'active' : 'pending');
    dot.innerHTML = i < n ? '<i class="bi bi-check2" style="font-size:0.82rem;"></i>' : i;
    const lbl = document.getElementById(`lbl-${i}`);
    lbl.className = 'step-label' + (i === n ? ' active' : '');
    if (i < total) document.getElementById(`line-${i}`).className = 'step-line ' + (i < n ? 'done' : 'pending');
  }
  document.getElementById('btn-prev').style.display = n > 1 ? 'block' : 'none';
  const nxt = document.getElementById('btn-next');
  nxt.innerHTML = n === total ? 'Register as Consultant <i class="bi bi-check2 ms-1"></i>' : 'Continue <i class="bi bi-arrow-right ms-1"></i>';
  current = n; hideMsg();
}

function nextStep() {
  if (!validateStep(current)) return;
  if (current === total) { document.getElementById('csignupForm').submit(); return; }
  setStep(current + 1);
}
function prevStep() { if (current > 1) setStep(current - 1); }

document.getElementById('pw1').addEventListener('input', function () {
  const v = this.value;
  const w = document.getElementById('strength-wrap');
  const bar = document.getElementById('strength-bar');
  const lbl = document.getElementById('strength-label');
  if (!v) { w.style.display = 'none'; return; }
  w.style.display = 'block';
  let s = 0;
  if (v.length >= 8) s++; if (/[A-Z]/.test(v)) s++; if (/[0-9]/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
  const map = ['', 'Weak', 'Fair', 'Good', 'Strong'];
  const clr = ['', '#ef4444', '#f59e0b', '#3b82f6', '#22c55e'];
  bar.style.width = (s * 25) + '%'; bar.style.background = clr[s];
  lbl.textContent = map[s]; lbl.style.color = clr[s];
});

function togglePw(id, iconId) {
  const el = document.getElementById(id); const ic = document.getElementById(iconId);
  el.type = el.type === 'password' ? 'text' : 'password';
  ic.className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  ic.style.fontSize = '0.88rem';
}

const p = new URLSearchParams(window.location.search);
if (p.get('msg')) showMsg(decodeURIComponent(p.get('msg')), p.get('type') === 'error');
</script>
