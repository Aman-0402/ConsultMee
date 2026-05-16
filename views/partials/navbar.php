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
          <a href="/dashboard" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Dashboard</a>
          <form method="post" action="/logout" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm hover:bg-red-500/30 transition-all">Logout</button>
          </form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Dashboard</a>
          <form method="post" action="/consultant/logout" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="px-4 py-2 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-sm hover:bg-red-500/30 transition-all">Logout</button>
          </form>
        <?php else: ?>
          <a href="/login" class="px-4 py-2 rounded-full border border-white/30 text-white text-sm hover:bg-white/10 transition-all">Login</a>
          <a href="/signup" class="px-4 py-2 rounded-full bg-gradient-to-r from-cobalt to-accent text-white text-sm font-semibold hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/20">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
