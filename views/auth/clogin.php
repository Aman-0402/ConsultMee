<main class="flex-1 flex items-center justify-center px-4 pt-24 pb-16">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <img src="/assets/img/logo.png" alt="ConsultMee" class="h-12 w-auto mx-auto mb-5">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-medium mb-4">
        <i class="bi bi-briefcase-fill"></i> Consultant Portal
      </div>
      <h1 class="text-2xl font-black mb-1">Consultant Login</h1>
      <p class="text-white/50 text-sm">Sign in to your consultant dashboard</p>
    </div>

    <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">
      <div id="messageBox" class="mb-4 text-center text-sm font-medium hidden rounded-xl px-4 py-3"></div>

      <form action="/consultant/login" method="POST" class="flex flex-col gap-5">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Email Address</label>
          <div class="relative">
            <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-white/30"></i>
            <input type="email" name="email" class="form-input pl-11" placeholder="your@email.com" required>
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-semibold text-white/80">Password</label>
            <a href="/consultant/forgot-password" class="text-xs text-accent hover:text-white transition-colors">Forgot password?</a>
          </div>
          <div class="relative">
            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/30"></i>
            <input type="password" name="password" id="passwordField" class="form-input pl-11 pr-11" placeholder="Enter your password" required>
            <button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/70 transition-colors">
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>
        <button type="submit" name="login" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 mt-2">
          Sign In <i class="bi bi-arrow-right ml-1"></i>
        </button>
      </form>

      <div class="mt-6 flex flex-col gap-3 text-center">
        <p class="text-white/50 text-sm">Don't have an account? <a href="/consultant/signup" class="text-accent hover:text-white font-medium transition-colors">Register as Consultant</a></p>
        <p class="text-white/30 text-xs">Are you a user? <a href="/login" class="text-white/50 hover:text-white transition-colors">User Login</a></p>
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
    box.innerHTML = decodeURIComponent(msg);
    box.style.color = type === 'error' ? '#f87171' : '#34d399';
    box.style.background = type === 'error' ? 'rgba(239,68,68,0.1)' : 'rgba(52,211,153,0.1)';
    box.style.padding = '10px 16px';
    box.style.borderRadius = '10px';
    box.style.border = type === 'error' ? '1px solid rgba(239,68,68,0.3)' : '1px solid rgba(52,211,153,0.3)';
  }
  function togglePwd() {
    const f = document.getElementById('passwordField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; i.className = 'bi bi-eye'; }
  }
</script>
