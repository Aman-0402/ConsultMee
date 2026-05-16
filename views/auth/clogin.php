<main class="flex-1 flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <a href="/"><img src="/assets/img/logo.png" alt="ConsultMee" class="h-11 w-auto mx-auto mb-6"></a>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold mb-4">
        <i class="bi bi-briefcase-fill"></i> Consultant Portal
      </span>
      <h1 class="text-2xl font-black text-slate-900 mb-1">Consultant Login</h1>
      <p class="text-slate-500 text-sm">Sign in to your consultant dashboard</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
      <div id="messageBox" class="mb-5 text-center text-sm font-semibold hidden rounded-xl px-4 py-3"></div>

      <form method="post" action="/consultant/login" class="flex flex-col gap-5">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Email Address</label>
          <div class="relative">
            <i class="bi bi-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="email" name="email" class="form-input pl-10" placeholder="your@email.com" required>
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold text-slate-700">Password</label>
            <a href="/consultant/forgot-password" class="text-xs text-cobalt hover:underline font-medium">Forgot password?</a>
          </div>
          <div class="relative">
            <i class="bi bi-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="password" name="password" class="form-input pl-10" placeholder="Enter password" required>
          </div>
        </div>
        <button type="submit" class="w-full py-3.5 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-sm mt-1">
          Sign In <i class="bi bi-arrow-right ml-1"></i>
        </button>
      </form>

      <div class="border-t border-slate-100 mt-6 pt-5 text-center">
        <p class="text-slate-500 text-sm">Not registered? <a href="/consultant/signup" class="text-cobalt hover:underline font-semibold">Register as Consultant</a></p>
        <p class="text-slate-400 text-xs mt-2">Are you a client? <a href="/login" class="text-cobalt hover:underline font-medium">Login here</a></p>
      </div>
    </div>
  </div>
</main>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get('msg'), type = urlParams.get('type');
  if (msg) {
    const box = document.getElementById('messageBox');
    box.classList.remove('hidden');
    box.textContent = decodeURIComponent(msg);
    if (type === 'error') { box.style.cssText = 'color:#dc2626;background:#fef2f2;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;'; }
    else { box.style.cssText = 'color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:12px 16px;border-radius:10px;'; }
  }
</script>
