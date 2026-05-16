<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-200" style="box-shadow:0 1px 8px rgba(0,0,0,0.06);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="/" class="flex items-center gap-2">
        <img src="/assets/img/logo.png" alt="ConsultMee" class="h-9 w-auto">
      </a>
      <div class="hidden md:flex items-center gap-1">
        <a href="/" class="px-4 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50 transition-all">Home</a>
        <a href="/services" class="px-4 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50 transition-all">Services</a>
        <a href="/about" class="px-4 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50 transition-all">About</a>
        <a href="/contact" class="px-4 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50 transition-all">Contact</a>
      </div>
      <div class="flex items-center gap-3">
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="/dashboard" class="hidden md:inline-flex px-4 py-2 rounded-lg border border-cobalt text-cobalt text-sm font-semibold hover:bg-blue-50 transition-all">Dashboard</a>
          <form method="post" action="/logout" class="hidden md:inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-100 transition-all">Logout</button>
          </form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="hidden md:inline-flex px-4 py-2 rounded-lg border border-cobalt text-cobalt text-sm font-semibold hover:bg-blue-50 transition-all">Dashboard</a>
          <form method="post" action="/consultant/logout" class="hidden md:inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-100 transition-all">Logout</button>
          </form>
        <?php else: ?>
          <a href="/login" class="hidden md:inline-flex px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-semibold hover:border-cobalt hover:text-cobalt transition-all">Login</a>
          <a href="/signup" class="hidden md:inline-flex px-5 py-2 rounded-lg bg-cobalt text-white text-sm font-semibold hover:bg-blue-700 transition-all shadow-sm">Get Started</a>
        <?php endif; ?>
        <button id="nav-toggle" class="md:hidden p-2 rounded-lg text-slate-600 hover:text-cobalt hover:bg-blue-50 transition-all" aria-label="Menu">
          <svg id="nav-icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg id="nav-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
  </div>
  <div id="nav-mobile" class="hidden md:hidden border-t border-slate-100 bg-white">
    <div class="px-4 py-3 flex flex-col gap-1">
      <a href="/" class="px-3 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50">Home</a>
      <a href="/services" class="px-3 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50">Services</a>
      <a href="/about" class="px-3 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50">About</a>
      <a href="/contact" class="px-3 py-2 rounded-lg text-slate-600 text-sm font-medium hover:text-cobalt hover:bg-blue-50">Contact</a>
      <div class="flex gap-2 pt-2 mt-1 border-t border-slate-100">
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="/dashboard" class="flex-1 text-center px-3 py-2 rounded-lg border border-cobalt text-cobalt text-sm font-semibold">Dashboard</a>
          <form method="post" action="/logout" class="flex-1"><?= csrf_field() ?><button type="submit" class="w-full px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold">Logout</button></form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="flex-1 text-center px-3 py-2 rounded-lg border border-cobalt text-cobalt text-sm font-semibold">Dashboard</a>
          <form method="post" action="/consultant/logout" class="flex-1"><?= csrf_field() ?><button type="submit" class="w-full px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold">Logout</button></form>
        <?php else: ?>
          <a href="/login" class="flex-1 text-center px-3 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-semibold">Login</a>
          <a href="/signup" class="flex-1 text-center px-3 py-2 rounded-lg bg-cobalt text-white text-sm font-semibold">Get Started</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<script>
(function() {
  const toggle = document.getElementById('nav-toggle');
  const menu   = document.getElementById('nav-mobile');
  const open   = document.getElementById('nav-icon-open');
  const close  = document.getElementById('nav-icon-close');
  if (toggle) toggle.addEventListener('click', () => {
    const hidden = menu.classList.toggle('hidden');
    open.classList.toggle('hidden', !hidden);
    close.classList.toggle('hidden', hidden);
  });
})();
</script>
