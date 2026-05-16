<!-- HERO -->
<section class="pt-28 pb-16 section-white border-b border-slate-100 text-center">
  <div class="max-w-3xl mx-auto px-4">
    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-cobalt text-xs font-semibold mb-6">
      <span class="w-1.5 h-1.5 rounded-full bg-cobalt animate-pulse"></span>
      What We Offer
    </span>
    <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">Our Services</h1>
    <p class="text-slate-500 text-lg">Expert consulting delivered directly by our team and platform, connecting clients with the right professionals.</p>
  </div>
</section>

<!-- IN-HOUSE SERVICES -->
<section class="section-gray py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-cobalt text-xs uppercase tracking-widest font-bold text-center mb-3">In-House Services</p>
    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 text-center mb-14">Expert Consulting Delivered Directly</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $inhouse = [
        ['icon'=>'bi-lightbulb','color'=>'blue','title'=>'Startup Consultation','desc'=>'Validate ideas, design business models, and launch startups with expert strategic guidance.'],
        ['icon'=>'bi-cpu','color'=>'emerald','title'=>'AI Consultation','desc'=>'Implement AI-driven automation, optimize workflows, and enhance business efficiency.'],
        ['icon'=>'bi-briefcase','color'=>'amber','title'=>'Business Consultation','desc'=>'Improve operations, scale growth strategies, and maximize profitability with expert insights.'],
        ['icon'=>'bi-mortarboard','color'=>'rose','title'=>'Training Consultation','desc'=>'Design structured training programs, institutional frameworks, and learning systems.'],
        ['icon'=>'bi-box-seam','color'=>'cyan','title'=>'Product Consultation','desc'=>'Plan, develop, and scale digital products with expert product strategy and roadmap guidance.'],
        ['icon'=>'bi-people','color'=>'violet','title'=>'HR Consultation','desc'=>'Build hiring systems, HR policies, and talent pipelines for sustainable workforce growth.'],
      ];
      $clr = ['blue'=>['bg'=>'bg-blue-50','border'=>'border-blue-100','text'=>'text-cobalt'],'emerald'=>['bg'=>'bg-emerald-50','border'=>'border-emerald-100','text'=>'text-emerald-600'],'amber'=>['bg'=>'bg-amber-50','border'=>'border-amber-100','text'=>'text-amber-500'],'rose'=>['bg'=>'bg-rose-50','border'=>'border-rose-100','text'=>'text-rose-500'],'cyan'=>['bg'=>'bg-cyan-50','border'=>'border-cyan-100','text'=>'text-cyan-600'],'violet'=>['bg'=>'bg-violet-50','border'=>'border-violet-100','text'=>'text-violet-600']];
      foreach ($inhouse as $s): $c = $clr[$s['color']]; ?>
      <div class="reveal pro-card p-7">
        <div class="w-14 h-14 rounded-2xl <?= $c['bg'] ?> border <?= $c['border'] ?> flex items-center justify-center mb-5">
          <i class="bi <?= $s['icon'] ?> text-2xl <?= $c['text'] ?>"></i>
        </div>
        <h5 class="font-bold text-slate-900 mb-2"><?= $s['title'] ?></h5>
        <p class="text-slate-500 text-sm leading-relaxed"><?= $s['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-12">
      <a href="/contact" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
        Request Consultation <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- PLATFORM SERVICES -->
<section class="section-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-cobalt text-xs uppercase tracking-widest font-bold text-center mb-3">Our Platform</p>
    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 text-center mb-14">Smart Solutions to Connect &amp; Collaborate</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $platform = [
        ['icon'=>'bi-calendar-check','color'=>'blue','title'=>'Appointment Booking','desc'=>'Schedule meetings with verified consultants seamlessly through our easy appointment system.'],
        ['icon'=>'bi-kanban','color'=>'emerald','title'=>'Project / Task Posting','desc'=>'Post your project requirements and receive proposals from expert consultants instantly.'],
        ['icon'=>'bi-person-lines-fill','color'=>'amber','title'=>'Consultant Suggestions','desc'=>'Get AI-driven recommendations to match you with the most suitable industry experts.'],
        ['icon'=>'bi-currency-rupee','color'=>'rose','title'=>'Affordable Pricing','desc'=>'Transparent and budget-friendly pricing models suitable for startups, MSMEs, and enterprises.'],
        ['icon'=>'bi-lightning-charge','color'=>'cyan','title'=>'One-Click Booking','desc'=>'Confirm and book your consultant instantly with a fast and hassle-free one-click process.'],
        ['icon'=>'bi-globe2','color'=>'violet','title'=>'Multi Industry Expertise','desc'=>'Access professionals across technology, legal, finance, healthcare, media, and more.'],
      ];
      foreach ($platform as $s): $c = $clr[$s['color']]; ?>
      <div class="reveal pro-card p-7">
        <div class="w-14 h-14 rounded-2xl <?= $c['bg'] ?> border <?= $c['border'] ?> flex items-center justify-center mb-5">
          <i class="bi <?= $s['icon'] ?> text-2xl <?= $c['text'] ?>"></i>
        </div>
        <h5 class="font-bold text-slate-900 mb-2"><?= $s['title'] ?></h5>
        <p class="text-slate-500 text-sm leading-relaxed"><?= $s['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-12">
      <a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:border-cobalt hover:text-cobalt transition-all">
        Explore All Services <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section-blue py-20">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">Need Expert Guidance?</h2>
    <p class="text-blue-100 mb-8 max-w-xl mx-auto">Join thousands of clients who trust ConsultMee to connect with the right consultants.</p>
    <div class="flex flex-wrap justify-center gap-4">
      <a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white text-cobalt font-bold hover:bg-blue-50 transition-all shadow-xl">
        Register <i class="bi bi-arrow-right"></i>
      </a>
      <a href="/contact" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border-2 border-white/40 text-white font-bold hover:bg-white/10 transition-all">
        Contact Us
      </a>
    </div>
  </div>
</section>

<script>
(function() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();
</script>
