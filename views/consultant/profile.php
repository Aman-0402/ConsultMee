<style>
  .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; transition: all 0.2s; }
  .info-box:hover { transform: translateY(-2px); border-color: #bfdbfe; background: #eff6ff; }
  .review-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
  .review-card:hover { transform: translateY(-2px); border-color: #bfdbfe; box-shadow: 0 4px 12px rgba(37,99,235,0.08); }
  .star-filled { color: #f59e0b; } .star-empty { color: #e2e8f0; }
  .pro-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 8px 24px rgba(0,0,0,0.05); }
</style>

<div class="max-w-5xl mx-auto px-4 pt-28 pb-16">

  <!-- PROFILE CARD -->
  <div class="pro-card p-8 mb-8">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
      <div class="flex flex-col items-center lg:w-56 shrink-0">
        <div class="relative">
          <img src="<?= UPLOAD_URL_CONSULTANTS . \ConsultMee\Core\View::escape($consultant['profile_img'] ?: 'default.png') ?>"
               alt="<?= \ConsultMee\Core\View::escape($consultant['name']) ?>"
               class="w-44 h-44 rounded-full object-cover border-4 border-blue-100 shadow-lg hover:scale-105 transition-transform">
          <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-400 border-2 border-white rounded-full"></span>
        </div>
        <div class="mt-4 flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-600">
          <i class="bi bi-star-fill text-sm"></i>
          <span class="font-bold text-sm"><?= $avgRating ?: 'N/A' ?>/5</span>
          <span class="text-amber-400 text-xs">(<?= $totalReviews ?>)</span>
        </div>
      </div>

      <div class="flex-1 min-w-0">
        <h1 class="text-3xl font-black tracking-tight text-slate-900 mb-0.5"><?= \ConsultMee\Core\View::escape($consultant['name']) ?></h1>
        <p class="text-slate-400 font-medium mb-4">@<?= \ConsultMee\Core\View::escape($consultant['username']) ?></p>

        <div class="flex flex-wrap gap-2 mb-6">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-cobalt text-white text-xs font-bold">
            <i class="bi bi-person-badge-fill"></i>
            <?= \ConsultMee\Core\View::escape($consultant['identity'] ?? 'Consultant') ?>
          </span>
          <?php if (!empty($consultant['area_of_expertise'])): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold">
            <i class="bi bi-briefcase-fill"></i>
            <?= \ConsultMee\Core\View::escape($consultant['area_of_expertise']) ?>
          </span>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
          <div class="info-box">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-geo-alt-fill me-1 text-cobalt"></i>State</p>
            <p class="text-slate-800 font-bold text-sm"><?= \ConsultMee\Core\View::escape($consultant['state'] ?? 'N/A') ?></p>
          </div>
          <div class="info-box">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-currency-rupee me-1 text-cobalt"></i>Hourly Rate</p>
            <p class="text-slate-800 font-bold text-sm">₹<?= \ConsultMee\Core\View::escape($consultant['hourly_rate'] ?? 'N/A') ?>/Hour</p>
          </div>
          <div class="info-box">
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1"><i class="bi bi-graph-up-arrow me-1 text-cobalt"></i>Experience</p>
            <p class="text-slate-800 font-bold text-sm"><?= \ConsultMee\Core\View::escape($consultant['experience'] ?? 'N/A') ?></p>
          </div>
        </div>

        <div>
          <h3 class="font-bold text-slate-900 mb-2 flex items-center gap-2"><i class="bi bi-info-circle-fill text-cobalt"></i> About</h3>
          <p class="text-slate-500 leading-relaxed text-sm">
            <?= !empty($consultant['bio']) ? nl2br(\ConsultMee\Core\View::escape($consultant['bio'])) : 'No bio available.' ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- BOOK CTA -->
  <div class="pro-card p-6 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div>
      <h3 class="font-bold text-slate-900 text-lg">Ready to consult?</h3>
      <p class="text-slate-500 text-sm">Book a session with <?= \ConsultMee\Core\View::escape($consultant['name']) ?> today.</p>
    </div>
    <a href="/dashboard#consultant-list" class="px-6 py-3 rounded-xl bg-cobalt text-white font-bold hover:bg-blue-700 transition-all shadow-sm whitespace-nowrap">
      <i class="bi bi-calendar-check me-2"></i>Book Appointment
    </a>
  </div>

  <!-- REVIEWS -->
  <div>
    <h2 class="text-xl font-black text-slate-900 mb-5 flex items-center gap-2">
      <i class="bi bi-chat-square-quote-fill text-cobalt"></i>
      Ratings &amp; Reviews
      <span class="text-sm font-semibold text-slate-400 ml-1">(<?= $totalReviews ?>)</span>
    </h2>

    <?php if ($totalReviews === 0): ?>
      <div class="pro-card p-8 text-center">
        <i class="bi bi-chat-left-dots text-slate-300 text-4xl block mb-3"></i>
        <p class="text-slate-400 font-semibold">No reviews yet. Be the first to review this consultant!</p>
      </div>
    <?php else: ?>
      <div class="flex flex-col gap-4">
        <?php foreach ($reviews as $r): ?>
        <div class="review-card">
          <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
            <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-xs font-black text-cobalt uppercase">
                <?= strtoupper(substr($r['user_username'], 0, 1)) ?>
              </span>
              <?= \ConsultMee\Core\View::escape($r['user_username']) ?>
            </span>
            <span class="text-slate-400 text-xs font-medium"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($r['created_at'])) ?></span>
          </div>
          <div class="flex items-center gap-1 mb-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="bi <?= $i <= (int)$r['rating'] ? 'bi-star-fill star-filled' : 'bi-star star-empty' ?>"></i>
            <?php endfor; ?>
            <span class="text-slate-400 text-xs ml-1 font-semibold">(<?= (int)$r['rating'] ?>/5)</span>
          </div>
          <p class="text-slate-500 text-sm leading-relaxed">
            <?= !empty($r['feedback']) ? \ConsultMee\Core\View::escape($r['feedback']) : 'No feedback given.' ?>
          </p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>
