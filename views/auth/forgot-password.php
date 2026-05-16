<main class="flex-1 flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <a href="/"><img src="/assets/img/logo.png" alt="ConsultMee" class="h-11 w-auto mx-auto mb-6"></a>
      <h1 class="text-2xl font-black text-slate-900 mb-1">Forgot Password</h1>
      <p class="text-slate-500 text-sm">Enter your email to receive a reset OTP</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
      <div id="messageBox" class="mb-5 text-center text-sm font-semibold hidden rounded-xl px-4 py-3"></div>

      <form id="sendOtpForm" class="flex flex-col gap-5">
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Email Address</label>
          <div class="relative">
            <i class="bi bi-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="email" id="email" name="email" class="form-input pl-10" placeholder="your@email.com" required>
          </div>
        </div>
        <button type="submit" class="w-full py-3.5 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-sm mt-1">
          Send OTP <i class="bi bi-arrow-right ml-1"></i>
        </button>
      </form>

      <form id="verifyOtpForm" class="hidden flex flex-col gap-5">
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">OTP Code</label>
          <div class="relative">
            <i class="bi bi-shield-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="otp" name="otp" class="form-input pl-10" placeholder="6-digit OTP" maxlength="6" required>
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">New Password</label>
          <div class="relative">
            <i class="bi bi-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="password" id="new_password" name="new_password" class="form-input pl-10" placeholder="Create new password" required>
          </div>
        </div>
        <input type="hidden" id="verify_email" name="email">
        <button type="submit" class="w-full py-3.5 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-sm mt-1">
          Reset Password <i class="bi bi-check-circle ml-1"></i>
        </button>
      </form>

      <div class="border-t border-slate-100 mt-6 pt-5 text-center">
        <p class="text-slate-500 text-sm">Remembered? <a href="/login" class="text-cobalt hover:underline font-semibold">Sign in</a></p>
      </div>
    </div>
  </div>
</main>

<script>
  const sendOtpForm = document.getElementById('sendOtpForm');
  const verifyOtpForm = document.getElementById('verifyOtpForm');
  const messageBox = document.getElementById('messageBox');

  function showMsg(text, type) {
    messageBox.classList.remove('hidden');
    messageBox.textContent = text;
    if (type === 'error') { messageBox.style.cssText = 'color:#dc2626;background:#fef2f2;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;'; }
    else if (type === 'success') { messageBox.style.cssText = 'color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:12px 16px;border-radius:10px;'; }
    else { messageBox.style.cssText = 'color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe;padding:12px 16px;border-radius:10px;'; }
  }

  sendOtpForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    showMsg('Sending OTP...', 'info');
    const formData = new FormData();
    formData.append('email', document.getElementById('email').value);
    const res = await fetch('/api/auth/send-otp', { method: 'POST', body: formData });
    let data;
    try { data = await res.json(); } catch { showMsg('Unexpected response.', 'error'); return; }
    if (data.success) {
      showMsg(data.success, 'success');
      document.getElementById('verify_email').value = document.getElementById('email').value;
      sendOtpForm.classList.add('hidden');
      verifyOtpForm.classList.remove('hidden');
    } else {
      showMsg(data.error || 'Failed to send OTP.', 'error');
    }
  });

  verifyOtpForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    showMsg('Verifying OTP...', 'info');
    const formData = new FormData();
    formData.append('email', document.getElementById('verify_email').value);
    formData.append('otp', document.getElementById('otp').value);
    formData.append('new_password', document.getElementById('new_password').value);
    const res = await fetch('/api/auth/verify-otp', { method: 'POST', body: formData });
    let data;
    try { data = await res.json(); } catch { showMsg('Unexpected server response.', 'error'); return; }
    if (data.success) {
      showMsg(data.success + ' Redirecting to login...', 'success');
      verifyOtpForm.querySelectorAll('input, button').forEach(el => el.disabled = true);
      setTimeout(() => { window.location.href = '/login'; }, 2000);
    } else {
      showMsg(data.error || 'OTP verification failed.', 'error');
    }
  });
</script>
