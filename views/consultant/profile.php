<style>
  .glass-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.15), transparent 65%); pointer-events: none; }
  .info-box { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 14px 16px; transition: all 0.2s; }
  .info-box:hover { transform: translateY(-2px); border-color: rgba(37,99,235,0.3); background: rgba(37,99,235,0.08); }
  .review-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); border-radius: 18px; padding: 20px; transition: all 0.2s; }
  .review-card:hover { transform: translateY(-2px); border-color: rgba(37,99,235,0.25); }
  .star-filled { color: #f59e0b; } .star-empty { color: rgba(255,255,255,0.2); }
</style>

<main class="max-w-5xl mx-auto px-4 pt-28 pb-16">

  <!-- PROFILE CARD -->
  <div class="glass-card p-8 mb-8 shadow-2xl">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <div class="flex flex-col items-center lg:w-56 shrink-0">
        <div class="relative">
          <img src="<?= UPLOAD_URL_CONSULTANTS . \ConsultMee\Core\View::escape($consultant['profile_img'] ?: 'default.png') ?>"
               alt="<?= \ConsultMee\Core\View::escape($consultant['name']) ?>"
               class="w-44 h-44 rounded-full object-cover border-4 border-cobalt/50 shadow-2xl shadow-blue-500/20 hover:scale-105 transition-transform">
          <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-400 border-2 border-white/20 rounded-full"></span>
        </div>
        <div class="mt-4 flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/25 text-amber-400">
          <i class="bi bi-star-fill text-sm"></i>
          <span class="font-bold text-sm"><?= $avgRating ?: 'N/A' ?>/5</span>
          <span class="text-amber-400/60 text-xs">(<?= $totalReviews ?>)</span>
        </div>
      </div>

      <div class="flex-1 min-w-0">
        <h1 class="text-3xl font-black tracking-tight mb-0.5"><?= \ConsultMee\Core\View::escape($consultant['name']) ?></h1>
        <p class="text-white/45 font-medium mb-4">@<?= \ConsultMee\Core\View::escape($consultant['username']) ?></p>

        <div class="flex flex-wrap gap-2 mb-6">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-cobalt to-accent text-white text-xs font-bold shadow-lg shadow-blue-500/20">
            <i class="bi bi-person-badge-fill"></i>
            <?= \ConsultMee\Core\View::escape($consultant['identity'] ?? 'Consultant') ?>
          </span>
          <?php if (!empty($consultant['area_of_expertise'])): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white text-xs font-bold">
            <i class="bi bi-briefcase-fill"></i>
            <?= \ConsultMee\Core\View::escape($consultant['area_of_expertise']) ?>
          </span>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
          <div class="info-box">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-geo-alt-fill me-1 text-cobalt"></i>State</p>
            <p class="text-white font-bold text-sm"><?= \ConsultMee\Core\View::escape($consultant['state'] ?? 'N/A') ?></p>
          </div>
          <div class="info-box">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-currency-rupee me-1 text-cobalt"></i>Hourly Rate</p>
            <p class="text-white font-bold text-sm">₹<?= \ConsultMee\Core\View::escape($consultant['hourly_rate'] ?? 'N/A') ?>/Hour</p>
          </div>
          <div class="info-box">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-graph-up-arrow me-1 text-cobalt"></i>Experience</p>
            <p class="text-white font-bold text-sm"><?= \ConsultMee\Core\View::escape($consultant['experience'] ?? 'N/A') ?></p>
          </div>
        </div>

        <div>
          <h3 class="font-bold text-white mb-2 flex items-center gap-2"><i class="bi bi-info-circle-fill text-cobalt"></i> About</h3>
          <p class="text-white/60 leading-relaxed text-sm">
            <?= !empty($consultant['bio']) ? nl2br(\ConsultMee\Core\View::escape($consultant['bio'])) : 'No bio available.' ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- BOOK CTA -->
  <div class="glass-card p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div>
      <h3 class="font-bold text-lg">Ready to consult?</h3>
      <p class="text-white/45 text-sm">Book a session with <?= \ConsultMee\Core\View::escape($consultant['name']) ?> today.</p>
    </div>
    <a href="/dashboard#consultant-list" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-bold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 whitespace-nowrap">
      <i class="bi bi-calendar-check me-2"></i>Book Appointment
    </a>
  </div>

  <!-- REVIEWS -->
  <div>
    <h2 class="text-xl font-black mb-5 flex items-center gap-2">
      <i class="bi bi-chat-square-quote-fill text-cobalt"></i>
      Ratings &amp; Reviews
      <span class="text-sm font-semibold text-white/35 ml-1">(<?= $totalReviews ?>)</span>
    </h2>

    <?php if ($totalReviews === 0): ?>
      <div class="glass-card p-8 text-center">
        <i class="bi bi-chat-left-dots text-white/20 text-4xl block mb-3"></i>
        <p class="text-white/45 font-semibold">No reviews yet. Be the first to review this consultant!</p>
      </div>
    <?php else: ?>
      <div class="flex flex-col gap-4">
        <?php foreach ($reviews as $r): ?>
        <div class="review-card">
          <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
            <span class="font-bold text-sm flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-cobalt/30 flex items-center justify-center text-xs font-black text-cobalt uppercase">
                <?= strtoupper(substr($r['user_username'], 0, 1)) ?>
              </span>
              <?= \ConsultMee\Core\View::escape($r['user_username']) ?>
            </span>
            <span class="text-white/35 text-xs font-medium"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($r['created_at'])) ?></span>
          </div>
          <div class="flex items-center gap-1 mb-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="bi <?= $i <= (int)$r['rating'] ? 'bi-star-fill star-filled' : 'bi-star star-empty' ?>"></i>
            <?php endfor; ?>
            <span class="text-white/35 text-xs ml-1 font-semibold">(<?= (int)$r['rating'] ?>/5)</span>
          </div>
          <p class="text-white/55 text-sm leading-relaxed">
            <?= !empty($r['feedback']) ? \ConsultMee\Core\View::escape($r['feedback']) : 'No feedback given.' ?>
          </p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</main>
