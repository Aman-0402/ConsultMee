<nav class="fixed top-0 left-0 right-0 z-50 bg-black/20 backdrop-blur-lg border-b border-white/10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="/">
        <img src="/assets/img/logo.png" alt="ConsultMee" class="h-9 w-auto">
      </a>
      <div class="hidden md:flex items-center gap-6">
        <a href="/" class="text-white/70 text-sm hover:text-white transition-colors">Home</a>
        <a href="/services" class="text-white/70 text-sm hover:text-white transition-colors">Services</a>
        <a href="/about" class="text-white/70 text-sm hover:text-white transition-colors">About Us</a>
        <a href="/contact" class="text-white/70 text-sm hover:text-white transition-colors">Contact</a>
      </div>
      <div class="flex items-center gap-3">
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="/dashboard" class="hidden md:inline-flex px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Dashboard</a>
          <form method="post" action="/logout" class="hidden md:inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm hover:bg-red-500/30 transition-all">Logout</button>
          </form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="hidden md:inline-flex px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Dashboard</a>
          <form method="post" action="/consultant/logout" class="hidden md:inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm hover:bg-red-500/30 transition-all">Logout</button>
          </form>
        <?php else: ?>
          <a href="/login" class="hidden md:inline-flex px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Login</a>
          <a href="/signup" class="hidden md:inline-flex px-4 py-2 rounded-full bg-gradient-to-r from-cobalt to-accent text-white text-sm font-semibold hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/20">Sign Up</a>
        <?php endif; ?>
        <button id="nav-toggle" class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-all" aria-label="Menu">
          <svg id="nav-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          <svg id="nav-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
  </div>
  <!-- Mobile menu -->
  <div id="nav-mobile" class="hidden md:hidden border-t border-white/10 bg-black/40 backdrop-blur-lg">
    <div class="px-4 py-4 flex flex-col gap-2">
      <a href="/" class="text-white/70 font-medium py-2 hover:text-white transition-colors">Home</a>
      <a href="/services" class="text-white/70 font-medium py-2 hover:text-white transition-colors">Services</a>
      <a href="/about" class="text-white/70 font-medium py-2 hover:text-white transition-colors">About Us</a>
      <a href="/contact" class="text-white/70 font-medium py-2 hover:text-white transition-colors">Contact</a>
      <div class="flex gap-3 pt-3 border-t border-white/10">
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="/dashboard" class="flex-1 text-center px-4 py-2 rounded-full border border-white/30 text-white text-sm">Dashboard</a>
          <form method="post" action="/logout" class="flex-1"><?= csrf_field() ?><button type="submit" class="w-full px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm">Logout</button></form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="flex-1 text-center px-4 py-2 rounded-full border border-white/30 text-white text-sm">Dashboard</a>
          <form method="post" action="/consultant/logout" class="flex-1"><?= csrf_field() ?><button type="submit" class="w-full px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm">Logout</button></form>
        <?php else: ?>
          <a href="/login" class="flex-1 text-center px-4 py-2 rounded-full border border-white/30 text-white text-sm">Login</a>
          <a href="/signup" class="flex-1 text-center px-4 py-2 rounded-full bg-gradient-to-r from-cobalt to-accent text-white text-sm font-semibold">Sign Up</a>
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
