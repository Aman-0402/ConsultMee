<style>
  .glass-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.13), transparent 65%); pointer-events: none; }
  .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.55s ease, transform 0.55s ease; }
  .reveal.visible { opacity: 1; transform: none; }
</style>

<!-- PAGE HERO -->
<section class="pt-32 pb-16" style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 60%, #0a1628 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cobalt/20 border border-cobalt/30 text-accent text-sm font-medium mb-6">
      <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
      What We Offer
    </span>
    <h1 class="text-4xl sm:text-5xl font-black mb-4">
      Our <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Services</span>
    </h1>
    <p class="text-white/55 text-lg max-w-2xl mx-auto">Expert consulting delivered directly by our team and platform, connecting clients with the right professionals.</p>
  </div>
</section>

<!-- IN-HOUSE SERVICES -->
<section class="py-20" style="background: #080d1c;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold text-center mb-3">In-House Services</p>
    <h2 class="text-3xl sm:text-4xl font-black text-center mb-14">Expert Consulting <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Delivered Directly</span></h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-lightbulb text-2xl text-blue-400"></i>
        </div>
        <h5 class="font-bold mb-2">Startup Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Validate ideas, design business models, and launch startups with expert strategic guidance.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-cpu text-2xl text-emerald-400"></i>
        </div>
        <h5 class="font-bold mb-2">AI Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Implement AI-driven automation, optimize workflows, and enhance business efficiency.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-briefcase text-2xl text-amber-400"></i>
        </div>
        <h5 class="font-bold mb-2">Business Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Improve operations, scale growth strategies, and maximize profitability with expert insights.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-mortarboard text-2xl text-rose-400"></i>
        </div>
        <h5 class="font-bold mb-2">Training Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Design structured training programs, institutional frameworks, and learning systems effectively.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-cyan-500/15 border border-cyan-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-box-seam text-2xl text-cyan-400"></i>
        </div>
        <h5 class="font-bold mb-2">Product Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Plan, develop, and scale digital products with expert product strategy and roadmap guidance.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-violet-500/15 border border-violet-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-people text-2xl text-violet-400"></i>
        </div>
        <h5 class="font-bold mb-2">HR Consultation</h5>
        <p class="text-white/50 text-sm leading-relaxed">Build hiring systems, HR policies, and talent pipelines for sustainable workforce growth.</p>
      </div>

    </div>

    <div class="text-center mt-12">
      <a href="/contact" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
        Request Consultation <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- PLATFORM SERVICES -->
<section class="py-20" style="background: #0d1530;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold text-center mb-3">Our Platform</p>
    <h2 class="text-3xl sm:text-4xl font-black text-center mb-14">Smart Solutions to <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Connect &amp; Collaborate</span></h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-calendar-check text-2xl text-blue-400"></i>
        </div>
        <h5 class="font-bold mb-2">Appointment Booking</h5>
        <p class="text-white/50 text-sm leading-relaxed">Schedule meetings with verified consultants seamlessly through our easy appointment system.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-kanban text-2xl text-emerald-400"></i>
        </div>
        <h5 class="font-bold mb-2">Project / Task Posting</h5>
        <p class="text-white/50 text-sm leading-relaxed">Post your project requirements and receive proposals from expert consultants instantly.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-person-lines-fill text-2xl text-amber-400"></i>
        </div>
        <h5 class="font-bold mb-2">Consultant Suggestions</h5>
        <p class="text-white/50 text-sm leading-relaxed">Get AI-driven recommendations to match you with the most suitable industry experts.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-currency-rupee text-2xl text-rose-400"></i>
        </div>
        <h5 class="font-bold mb-2">Affordable Pricing</h5>
        <p class="text-white/50 text-sm leading-relaxed">Transparent and budget-friendly pricing models suitable for startups, MSMEs, and enterprises.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-cyan-500/15 border border-cyan-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-lightning-charge text-2xl text-cyan-400"></i>
        </div>
        <h5 class="font-bold mb-2">One-Click Booking</h5>
        <p class="text-white/50 text-sm leading-relaxed">Confirm and book your consultant instantly with a fast and hassle-free one-click process.</p>
      </div>

      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-violet-500/15 border border-violet-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-globe2 text-2xl text-violet-400"></i>
        </div>
        <h5 class="font-bold mb-2">Multi Industry Expertise</h5>
        <p class="text-white/50 text-sm leading-relaxed">Access professionals across technology, legal, finance, healthcare, media, and more.</p>
      </div>

    </div>

    <div class="text-center mt-12">
      <a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
        Explore All Services <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-16" style="background: linear-gradient(135deg, #0d1a3a 0%, #0a1628 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="glass-card bg-white/5 backdrop-blur-xl border border-white/15 rounded-3xl p-10 md:p-14 text-center">
      <h4 class="text-2xl md:text-3xl font-black mb-4">Need Expert Guidance?</h4>
      <p class="text-white/60 mb-8 max-w-xl mx-auto">Join thousands of clients who trust ConsultMee to connect with the right consultants across industries.</p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
          Register <i class="bi bi-arrow-right"></i>
        </a>
        <a href="/contact" class="inline-flex items-center gap-2 px-8 py-4 rounded-full border border-white/25 text-white font-semibold hover:bg-white/10 transition-all">
          Contact Us
        </a>
      </div>
    </div>
  </div>
</section>

<script>
(function() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();
</script>
