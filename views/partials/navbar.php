<?php $cp = strtok($_SERVER['REQUEST_URI'], '?'); ?>
<style>
/* ═══════════════════════════════════════════════
   FLOATING NAV WRAPPER
═══════════════════════════════════════════════ */
#nav-wrap {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  padding: 14px 16px 0;
  pointer-events: none;
  animation: navWrapIn 0.6s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes navWrapIn {
  from { opacity:0; transform:translateY(-24px); }
  to   { opacity:1; transform:translateY(0); }
}

/* ─── FLOATING PILL ─── */
#main-nav {
  pointer-events: all;
  max-width: 1280px; margin: 0 auto;
  background: rgba(15,40,84,0.88);
  backdrop-filter: blur(28px) saturate(180%);
  -webkit-backdrop-filter: blur(28px) saturate(180%);
  border: 1px solid rgba(73,136,196,0.25);
  border-radius: 18px;
  box-shadow:
    0 4px 28px rgba(15,40,84,0.45),
    0 1px 0 rgba(189,232,245,0.08) inset;
  transition: background 0.3s, box-shadow 0.3s, border-color 0.3s;
  overflow: visible;
}
#main-nav.scrolled {
  background: rgba(15,40,84,0.96);
  box-shadow:
    0 8px 40px rgba(15,40,84,0.55),
    0 1px 0 rgba(189,232,245,0.1) inset;
  border-color: rgba(73,136,196,0.35);
}

/* ─── INNER BAR ─── */
.nav-inner {
  display: flex; align-items: center; justify-content: space-between;
  height: 70px; padding: 0 24px; gap: 10px;
}

/* ─── LOGO ─── */
.nav-logo { flex-shrink: 0; display:flex; align-items:center; }
.nav-logo img {
  height: 42px; width: auto;
  filter: brightness(0) invert(1) drop-shadow(0 0 10px rgba(99,149,255,0.4));
  transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), filter 0.3s;
}
.nav-logo:hover img { transform: scale(1.06); filter: brightness(0) invert(1) drop-shadow(0 0 16px rgba(99,149,255,0.7)); }

/* ─── DESKTOP LINKS ─── */
.nav-links {
  display: flex; align-items: center; gap: 2px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.09);
  border-radius: 12px;
  padding: 4px;
}
.nav-link {
  position: relative;
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 16px;
  border-radius: 9px;
  font-size: 0.875rem; font-weight: 600;
  color: rgba(255,255,255,0.65); text-decoration: none;
  transition: color 0.2s, background 0.2s, transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.2s;
  white-space: nowrap;
}
.nav-link:hover {
  color: #ffffff;
  background: rgba(255,255,255,0.1);
  transform: translateY(-1px);
  box-shadow: 0 2px 12px rgba(0,0,0,0.2);
}
.nav-link:active { transform: scale(0.97); }

/* active pill */
.nav-link.active {
  color: #ffffff;
  background: rgba(28,77,141,0.7);
  box-shadow: 0 2px 14px rgba(73,136,196,0.4), 0 1px 0 rgba(189,232,245,0.12) inset;
}
.nav-link .active-bar {
  position: absolute; bottom: 3px; left: 50%; transform: translateX(-50%);
  width: 18px; height: 2.5px; border-radius: 3px;
  background: linear-gradient(90deg, #4988c4, #bde8f5);
  box-shadow: 0 0 8px rgba(189,232,245,0.6);
  animation: barGrow 0.35s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes barGrow {
  from { width:0; opacity:0; }
  to   { width:18px; opacity:1; }
}

/* ─── CTA ZONE ─── */
.nav-cta { display: flex; align-items: center; gap: 8px; flex-shrink:0; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 16px; border-radius: 10px;
  border: 1.5px solid rgba(255,255,255,0.2);
  background: rgba(255,255,255,0.07);
  color: rgba(255,255,255,0.85); font-size: 0.83rem; font-weight: 600;
  text-decoration: none; cursor: pointer;
  transition: all 0.22s cubic-bezier(0.22,1,0.36,1);
  white-space: nowrap;
}
.btn-ghost:hover {
  border-color: rgba(255,255,255,0.4); color: #ffffff;
  background: rgba(255,255,255,0.13);
  transform: translateY(-1px);
  box-shadow: 0 3px 14px rgba(0,0,0,0.25);
}

.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 18px; border-radius: 10px;
  background: linear-gradient(135deg, #1c4d8d 0%, #4988c4 60%, #bde8f5 100%);
  background-size: 200% 100%; background-position: 0% 0;
  color: #fff; font-size: 0.83rem; font-weight: 700;
  text-decoration: none; border: none; cursor: pointer;
  box-shadow: 0 3px 14px rgba(28,77,141,0.5), inset 0 1px 0 rgba(255,255,255,0.2);
  transition: all 0.28s cubic-bezier(0.22,1,0.36,1), background-position 0.4s;
  white-space: nowrap; position: relative; overflow: hidden;
}
.btn-primary:hover {
  background-position: 100% 0;
  transform: translateY(-2px) scale(1.02);
  box-shadow: 0 8px 28px rgba(28,77,141,0.6), inset 0 1px 0 rgba(255,255,255,0.25);
}
.btn-primary:active { transform: scale(0.97); }

.btn-danger {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 7px 14px; border-radius: 10px;
  border: 1.5px solid rgba(248,113,113,0.25);
  background: rgba(220,38,38,0.15);
  color: #fca5a5; font-size: 0.83rem; font-weight: 600;
  cursor: pointer; transition: all 0.22s; white-space: nowrap;
}
.btn-danger:hover {
  background: rgba(220,38,38,0.28); border-color: rgba(248,113,113,0.45);
  color: #fecaca;
  transform: translateY(-1px); box-shadow: 0 3px 14px rgba(220,38,38,0.25);
}

/* ─── HAMBURGER ─── */
.ham {
  display: none; flex-direction: column; justify-content: center; align-items: center;
  width: 38px; height: 38px; border-radius: 10px; gap: 5px;
  border: 1.5px solid rgba(255,255,255,0.15);
  background: rgba(255,255,255,0.08);
  cursor: pointer; transition: all 0.22s; flex-shrink:0;
}
.ham:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); }
.ham-l {
  display: block; height: 1.75px; border-radius: 2px;
  background: rgba(255,255,255,0.75); transition: all 0.32s cubic-bezier(0.22,1,0.36,1); transform-origin: center;
}
.ham-l:nth-child(1) { width: 20px; }
.ham-l:nth-child(2) { width: 14px; }
.ham-l:nth-child(3) { width: 18px; }
.ham.is-open .ham-l:nth-child(1) { width:20px; transform: translateY(6.75px) rotate(45deg); background:#bde8f5; }
.ham.is-open .ham-l:nth-child(2) { opacity:0; transform: scaleX(0); }
.ham.is-open .ham-l:nth-child(3) { width:20px; transform: translateY(-6.75px) rotate(-45deg); background:#bde8f5; }

/* ═══════════════════════════════════════════════
   MOBILE OVERLAY + PANEL
═══════════════════════════════════════════════ */
#mob-overlay {
  display: none; position: fixed; inset: 0; z-index: 998;
  background: rgba(15,40,84,0.3);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  animation: fadeOverlay 0.25s ease both;
}
@keyframes fadeOverlay { from{opacity:0;} to{opacity:1;} }
#mob-overlay.closing { animation: fadeOverlay 0.2s ease reverse both; }

#mob-panel {
  display: none; position: fixed; z-index: 999;
  top: 84px; left: 16px; right: 16px;
  background: rgba(255,255,255,0.96);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(148,163,184,0.2);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(15,23,42,0.18), 0 1px 0 rgba(255,255,255,0.9) inset;
  padding: 16px;
  transform-origin: top center;
  animation: panelIn 0.38s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes panelIn {
  from { opacity:0; transform: scale(0.94) translateY(-12px); }
  to   { opacity:1; transform: scale(1) translateY(0); }
}
#mob-panel.closing { animation: panelIn 0.22s cubic-bezier(0.22,1,0.36,1) reverse both; }

/* mob links */
.mob-link {
  display: flex; align-items: center; gap: 12px;
  padding: 11px 14px; border-radius: 13px;
  font-size: 0.9rem; font-weight: 600; color: #475569;
  text-decoration: none; transition: all 0.2s; position: relative;
  margin-bottom: 2px;
}
.mob-link:hover { background: rgba(28,77,141,0.07); color: #1c4d8d; transform: translateX(3px); }
.mob-link.active { background: rgba(28,77,141,0.09); color: #1c4d8d; }
.mob-link .m-icon {
  width: 34px; height: 34px; border-radius: 10px; display:flex;
  align-items:center; justify-content:center; font-size:0.95rem; flex-shrink:0;
  transition: transform 0.2s cubic-bezier(0.34,1.56,0.64,1);
}
.mob-link:hover .m-icon, .mob-link.active .m-icon { transform: scale(1.1); }
.mob-link .m-badge {
  margin-left: auto; width: 6px; height: 6px; border-radius: 50%;
  background: linear-gradient(135deg,#1c4d8d,#bde8f5);
  box-shadow: 0 0 6px rgba(37,99,235,0.5);
  animation: pulseDot 1.8s ease-in-out infinite;
}
@keyframes pulseDot {
  0%,100% { box-shadow:0 0 4px 1px rgba(37,99,235,0.4); }
  50%      { box-shadow:0 0 10px 3px rgba(37,99,235,0.2); }
}

.mob-divider { height:1px; background: rgba(226,232,240,0.7); margin: 10px 0; }
.mob-cta { display:flex; flex-direction:column; gap:8px; }

/* ─── RESPONSIVE ─── */
@media (max-width: 767px) {
  .nav-links, .nav-cta { display: none !important; }
  .ham { display: flex; }
  #nav-wrap { padding: 10px 12px 0; }
}
@media (min-width: 768px) {
  .ham { display: none; }
  #mob-overlay, #mob-panel { display: none !important; }
}

/* stagger entrance for desktop links */
.nav-link { opacity:0; animation: linkIn 0.4s cubic-bezier(0.22,1,0.36,1) forwards; }
@keyframes linkIn { from{opacity:0;transform:translateY(-6px);} to{opacity:1;transform:none;} }
</style>

<!-- OVERLAY -->
<div id="mob-overlay"></div>
<!-- MOBILE PANEL -->
<div id="mob-panel">
  <?php
  $navLinks = ['/' => ['Home','bi-house-fill'], '/services' => ['Services','bi-briefcase-fill'], '/about' => ['About','bi-info-circle-fill'], '/contact' => ['Contact','bi-envelope-fill']];
  $iconClrs = ['/' => '#1c4d8d:#e8f0fb', '/services' => '#1c4d8d:#e8f0fb', '/about' => '#4988c4:#edf4fb', '/contact' => '#0f2854:#ddeaf5'];
  foreach ($navLinks as $href => [$label, $icon]):
    $active = ($href==='/' ? $cp==='/' : str_starts_with($cp,$href));
    [$clr,$bg] = explode(':', $iconClrs[$href]);
  ?>
    <a href="<?= $href ?>" class="mob-link <?= $active?'active':'' ?>">
      <span class="m-icon" style="background:<?= $bg ?>;color:<?= $clr ?>;"><i class="bi <?= $icon ?>"></i></span>
      <?= $label ?>
      <?php if($active): ?><span class="m-badge"></span><?php endif; ?>
    </a>
  <?php endforeach; ?>
  <div class="mob-divider"></div>
  <div class="mob-cta">
    <?php if (!empty($_SESSION['user'])): ?>
      <a href="/dashboard" class="btn-ghost" style="justify-content:center;"><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</a>
      <form method="post" action="/logout"><?= csrf_field() ?><button type="submit" class="btn-danger w-full" style="justify-content:center;"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
    <?php elseif (!empty($_SESSION['consultant'])): ?>
      <a href="/consultant/dashboard" class="btn-ghost" style="justify-content:center;"><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</a>
      <form method="post" action="/consultant/logout"><?= csrf_field() ?><button type="submit" class="btn-danger w-full" style="justify-content:center;"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
    <?php else: ?>
      <a href="/login" class="btn-ghost" style="justify-content:center;">Login</a>
      <a href="/signup" class="btn-primary" style="justify-content:center;">Get Started</a>
    <?php endif; ?>
  </div>
</div>

<!-- NAV WRAP + PILL -->
<div id="nav-wrap">
  <nav id="main-nav">
    <div class="nav-inner">

      <!-- LOGO -->
      <a href="/" class="nav-logo">
        <img src="/assets/img/logo.png" alt="ConsultMee">
      </a>

      <!-- DESKTOP LINKS -->
      <div class="nav-links">
        <?php
        $delay = 0.08;
        foreach ($navLinks as $href => [$label, $icon]):
          $active = ($href==='/' ? $cp==='/' : str_starts_with($cp,$href));
        ?>
          <a href="<?= $href ?>"
             class="nav-link <?= $active?'active':'' ?>"
             style="animation-delay:<?= $delay ?>s;">
            <?= $label ?>
            <?php if($active): ?><span class="active-bar"></span><?php endif; ?>
          </a>
        <?php $delay += 0.06; endforeach; ?>
      </div>

      <!-- DESKTOP CTA -->
      <div class="nav-cta">
        <?php if (!empty($_SESSION['user'])): ?>
          <a href="/dashboard" class="btn-ghost"><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</a>
          <form method="post" action="/logout"><?= csrf_field() ?><button type="submit" class="btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
        <?php elseif (!empty($_SESSION['consultant'])): ?>
          <a href="/consultant/dashboard" class="btn-ghost"><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</a>
          <form method="post" action="/consultant/logout"><?= csrf_field() ?><button type="submit" class="btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
        <?php else: ?>
          <a href="/login" class="btn-ghost">Login</a>
          <a href="/signup" class="btn-primary">Get Started</a>
        <?php endif; ?>
      </div>

      <!-- HAMBURGER -->
      <button class="ham" id="ham-btn" aria-label="Toggle menu" aria-expanded="false">
        <span class="ham-l"></span>
        <span class="ham-l"></span>
        <span class="ham-l"></span>
      </button>

    </div>
  </nav>
</div>

<script>
(function () {
  const nav      = document.getElementById('main-nav');
  const hamBtn   = document.getElementById('ham-btn');
  const overlay  = document.getElementById('mob-overlay');
  const panel    = document.getElementById('mob-panel');
  let isOpen = false;

  /* scroll: deepen glass */
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 30);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* open/close */
  function openMenu() {
    isOpen = true;
    panel.style.display   = 'block';
    overlay.style.display = 'block';
    panel.classList.remove('closing');
    overlay.classList.remove('closing');
    hamBtn.classList.add('is-open');
    hamBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    if (!isOpen) return;
    isOpen = false;
    panel.classList.add('closing');
    overlay.classList.add('closing');
    hamBtn.classList.remove('is-open');
    hamBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    setTimeout(() => {
      panel.style.display   = 'none';
      overlay.style.display = 'none';
      panel.classList.remove('closing');
      overlay.classList.remove('closing');
    }, 230);
  }

  hamBtn.addEventListener('click', (e) => { e.stopPropagation(); isOpen ? closeMenu() : openMenu(); });
  overlay.addEventListener('click', closeMenu);

  /* close on resize to desktop */
  window.addEventListener('resize', () => { if (window.innerWidth >= 768) closeMenu(); });

  /* ripple on primary btn */
  document.querySelectorAll('.btn-primary').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const r = document.createElement('span');
      const d = Math.max(btn.offsetWidth, btn.offsetHeight);
      const rc = btn.getBoundingClientRect();
      r.style.cssText = `position:absolute;border-radius:50%;width:${d}px;height:${d}px;
        left:${e.clientX-rc.left-d/2}px;top:${e.clientY-rc.top-d/2}px;
        background:rgba(255,255,255,0.28);pointer-events:none;
        animation:rpl 0.5s ease-out forwards;`;
      btn.appendChild(r);
      setTimeout(() => r.remove(), 520);
    });
  });
  const ks = document.createElement('style');
  ks.textContent = '@keyframes rpl{to{transform:scale(2.8);opacity:0;}}';
  document.head.appendChild(ks);
})();
</script>
