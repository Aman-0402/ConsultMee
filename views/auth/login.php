<style>
  body { margin:0; min-height:100vh; background:#0f2854; font-family:'Inter',system-ui,sans-serif; }

  /* bg blobs */
  .blob { position:fixed; border-radius:50%; filter:blur(80px); pointer-events:none; z-index:0; }
  .blob-1 { width:500px; height:500px; top:-120px; left:-100px; background:rgba(73,136,196,0.3); }
  .blob-2 { width:400px; height:400px; bottom:-80px; right:-80px; background:rgba(28,77,141,0.4); }
  .blob-3 { width:300px; height:300px; top:50%; left:50%; transform:translate(-50%,-50%); background:rgba(189,232,245,0.07); }

  /* back btn */
  .back-btn {
    position:fixed; top:24px; left:24px; z-index:100;
    display:inline-flex; align-items:center; gap:7px;
    padding:8px 16px; border-radius:10px;
    border:1px solid rgba(189,232,245,0.25);
    background:rgba(255,255,255,0.08);
    color:rgba(189,232,245,0.85); font-size:0.83rem; font-weight:600;
    text-decoration:none; backdrop-filter:blur(12px);
    transition:all 0.22s;
  }
  .back-btn:hover { background:rgba(255,255,255,0.15); color:#bde8f5; border-color:rgba(189,232,245,0.45); transform:translateX(-2px); }

  /* page grid */
  .page-grid { display:grid; grid-template-columns:1fr 1fr; min-height:100vh; position:relative; z-index:1; }

  /* left panel */
  .left-panel { display:flex; flex-direction:column; justify-content:center; padding:80px 60px; }
  .left-panel .tagline { font-size:2.6rem; font-weight:900; color:#fff; line-height:1.15; letter-spacing:-0.04em; margin-bottom:16px; }
  .left-panel .tagline span { color:#bde8f5; }
  .left-panel .sub { color:rgba(189,232,245,0.65); font-size:1rem; line-height:1.6; max-width:340px; margin-bottom:40px; }
  .feature-item { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
  .feature-icon { width:36px; height:36px; border-radius:10px; background:rgba(73,136,196,0.25); border:1px solid rgba(73,136,196,0.3); display:flex; align-items:center; justify-content:center; color:#bde8f5; font-size:0.9rem; flex-shrink:0; }
  .feature-text { color:rgba(189,232,245,0.75); font-size:0.875rem; font-weight:500; }

  /* right panel */
  .right-panel { display:flex; align-items:center; justify-content:center; padding:40px 40px 40px 20px; }
  .auth-card { width:100%; max-width:420px; background:rgba(255,255,255,0.97); border-radius:24px; padding:40px; box-shadow:0 30px 80px rgba(0,0,0,0.4), 0 1px 0 rgba(255,255,255,0.8) inset; }

  /* form */
  .field-wrap { position:relative; }
  .field-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#4988c4; font-size:0.95rem; pointer-events:none; }
  .form-input { width:100%; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:11px; padding:12px 16px 12px 40px; color:#0f172a; font-size:0.875rem; outline:none; transition:border-color 0.2s,box-shadow 0.2s,background 0.2s; box-sizing:border-box; }
  .form-input:focus { border-color:#1c4d8d; box-shadow:0 0 0 3px rgba(28,77,141,0.12); background:#fff; }
  .form-input::placeholder { color:#94a3b8; }
  .btn-submit { width:100%; padding:13px; border-radius:12px; background:linear-gradient(135deg,#0f2854,#1c4d8d 55%,#4988c4); color:#fff; font-size:0.95rem; font-weight:700; border:none; cursor:pointer; box-shadow:0 6px 24px rgba(15,40,84,0.45); transition:all 0.28s cubic-bezier(0.22,1,0.36,1); }
  .btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 36px rgba(15,40,84,0.55); filter:brightness(1.1); }
  .btn-submit:active { transform:scale(0.98); }
  .divider { height:1px; background:linear-gradient(90deg,transparent,#e2e8f0,transparent); margin:20px 0; }

  /* mobile: stack */
  @media(max-width:768px) {
    .page-grid { grid-template-columns:1fr; }
    .left-panel { display:none; }
    .right-panel { padding:100px 20px 40px; }
    .auth-card { padding:28px 24px; }
    .back-btn { top:16px; left:16px; }
  }
</style>

<a href="/" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="page-grid">

  <!-- LEFT BRAND PANEL -->
  <div class="left-panel">
    <a href="/"><img src="/assets/img/logo.png" alt="ConsultMee" style="height:44px;width:auto;filter:brightness(0) invert(1);margin-bottom:40px;"></a>
    <div class="tagline">Expert advice,<br><span>when you need it.</span></div>
    <p class="sub">Connect with verified consultants across 30+ industries and get professional guidance tailored to your goals.</p>
    <div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-patch-check-fill"></i></span><span class="feature-text">Verified & certified consultants</span></div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-shield-lock-fill"></i></span><span class="feature-text">Secure & encrypted sessions</span></div>
      <div class="feature-item"><span class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></span><span class="feature-text">Book appointments instantly</span></div>
    </div>
  </div>

  <!-- RIGHT FORM PANEL -->
  <div class="right-panel">
    <div class="auth-card">

      <div style="margin-bottom:28px;">
        <h1 style="font-size:1.65rem;font-weight:900;color:#0f2854;letter-spacing:-0.03em;margin:0 0 6px;">Welcome back</h1>
        <p style="color:#64748b;font-size:0.875rem;margin:0;">Sign in to your ConsultMee account</p>
      </div>

      <div id="messageBox" class="mb-5 hidden" style="border-radius:10px;padding:12px 16px;font-size:0.875rem;font-weight:600;text-align:center;"></div>

      <form method="post" action="/login" style="display:flex;flex-direction:column;gap:16px;">
        <?= csrf_field() ?>

        <div>
          <label style="display:block;font-size:0.82rem;font-weight:700;color:#0f2854;margin-bottom:7px;letter-spacing:0.01em;">EMAIL ADDRESS</label>
          <div class="field-wrap">
            <i class="bi bi-envelope-fill field-icon"></i>
            <input type="email" name="email" class="form-input" placeholder="your@email.com" required autocomplete="email">
          </div>
        </div>

        <div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:7px;">
            <label style="font-size:0.82rem;font-weight:700;color:#0f2854;letter-spacing:0.01em;">PASSWORD</label>
            <a href="/forgot-password" style="font-size:0.78rem;font-weight:600;color:#4988c4;text-decoration:none;" onmouseover="this.style.color='#1c4d8d'" onmouseout="this.style.color='#4988c4'">Forgot password?</a>
          </div>
          <div class="field-wrap">
            <i class="bi bi-lock-fill field-icon"></i>
            <input type="password" name="password" id="password" class="form-input" placeholder="Enter your password" required autocomplete="current-password">
            <button type="button" onclick="togglePass()" tabindex="-1" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:4px;line-height:1;">
              <i id="eye-icon" class="bi bi-eye" style="font-size:0.95rem;"></i>
            </button>
          </div>
        </div>

        <button type="submit" name="login" class="btn-submit" style="margin-top:4px;">
          Sign In &nbsp;<i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="divider"></div>

      <div style="text-align:center;font-size:0.875rem;">
        <p style="color:#64748b;margin:0 0 8px;">Don't have an account? <a href="/signup" style="color:#1c4d8d;font-weight:700;text-decoration:none;" onmouseover="this.style.color='#4988c4'" onmouseout="this.style.color='#1c4d8d'">Sign up free</a></p>
        <p style="color:#94a3b8;font-size:0.78rem;margin:0;">Are you a consultant? <a href="/consultant/login" style="color:#4988c4;font-weight:600;text-decoration:none;" onmouseover="this.style.color='#1c4d8d'" onmouseout="this.style.color='#4988c4'">Login here</a></p>
      </div>

      <div style="display:flex;justify-content:center;gap:20px;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
        <span style="color:#94a3b8;font-size:0.72rem;font-weight:600;display:flex;align-items:center;gap:4px;"><i class="bi bi-shield-check" style="color:#4988c4;"></i>Secure</span>
        <span style="color:#94a3b8;font-size:0.72rem;font-weight:600;display:flex;align-items:center;gap:4px;"><i class="bi bi-lock" style="color:#4988c4;"></i>Encrypted</span>
        <span style="color:#94a3b8;font-size:0.72rem;font-weight:600;display:flex;align-items:center;gap:4px;"><i class="bi bi-patch-check" style="color:#4988c4;"></i>Verified</span>
      </div>

    </div>
  </div>

</div>

<script>
  function togglePass() {
    const pw = document.getElementById('password');
    const ic = document.getElementById('eye-icon');
    pw.type = pw.type === 'password' ? 'text' : 'password';
    ic.className = pw.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }
  const p = new URLSearchParams(window.location.search);
  const msg = p.get('msg'), type = p.get('type');
  if (msg) {
    const b = document.getElementById('messageBox');
    b.classList.remove('hidden'); b.style.display='block';
    b.textContent = decodeURIComponent(msg);
    b.style.cssText += type==='error'
      ? 'color:#dc2626;background:#fef2f2;border:1px solid #fecaca;'
      : 'color:#0f2854;background:#e8f0fb;border:1px solid #bde8f5;';
  }
</script>
