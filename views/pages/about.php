<style>
  .glass-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.13), transparent 65%); pointer-events: none; }
  .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.55s ease, transform 0.55s ease; }
  .reveal.visible { opacity: 1; transform: none; }
</style>

<!-- HERO -->
<section class="pt-32 pb-20" style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 60%, #0a1628 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cobalt/20 border border-cobalt/30 text-accent text-sm font-medium mb-6">
          <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
          About ConsultMee
        </span>
        <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-6">
          Connect With Verified Experts<br>
          <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">For Smarter Decisions</span>
        </h1>
        <p class="text-white/60 text-lg mb-8 leading-relaxed">
          ConsultMee is India's smart consultation marketplace helping individuals, startups, and enterprises book experts, post projects, and grow faster with professional guidance.
        </p>
        <div class="flex flex-wrap gap-4 mb-8">
          <a href="/services" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
            Explore Services <i class="bi bi-arrow-right"></i>
          </a>
          <a href="/signup" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold hover:bg-white/15 transition-all">
            Get Free Consultation
          </a>
        </div>
        <div class="flex flex-wrap gap-3">
          <span class="px-4 py-1.5 rounded-full bg-white/8 border border-white/15 text-white/70 text-sm">Appointment Booking</span>
          <span class="px-4 py-1.5 rounded-full bg-white/8 border border-white/15 text-white/70 text-sm">AI Consultant Suggestions</span>
          <span class="px-4 py-1.5 rounded-full bg-white/8 border border-white/15 text-white/70 text-sm">Multi-Industry Experts</span>
        </div>
      </div>

      <!-- Quick Contact card -->
      <div>
        <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">
          <h4 class="text-xl font-bold mb-2">Quick Contact</h4>
          <p class="text-white/50 mb-7 text-sm">Connect with our platform team today.</p>
          <div class="flex items-center gap-4 mb-5">
            <div class="w-11 h-11 rounded-xl bg-cobalt/20 border border-cobalt/30 flex items-center justify-center flex-shrink-0">
              <i class="bi bi-envelope-fill text-accent"></i>
            </div>
            <span class="text-white/80">support@consultmee.in</span>
          </div>
          <div class="flex items-center gap-4 mb-8">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center flex-shrink-0">
              <i class="bi bi-telephone-fill text-emerald-400"></i>
            </div>
            <a href="tel:918317818107" class="text-white/80 hover:text-white transition-colors">+91 8317818107</a>
          </div>
          <a href="/contact" class="block w-full text-center px-6 py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/30">
            Contact Now
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- MISSION & VISION -->
<section class="py-20" style="background: #080d1c;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold mb-3">Who We Are</p>
    <h2 class="text-3xl sm:text-4xl font-black mb-14">
      Our <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Mission &amp; Vision</span>
    </h2>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 text-left hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-bullseye text-2xl text-blue-400"></i>
        </div>
        <h5 class="text-xl font-bold mb-3">Our Mission</h5>
        <p class="text-white/55 leading-relaxed">To simplify access to expert consultancy and mentorship for everyone — empowering individuals and businesses to make informed, strategic, and confident decisions.</p>
      </div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 text-left hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mb-5">
          <i class="bi bi-eye text-2xl text-emerald-400"></i>
        </div>
        <h5 class="text-xl font-bold mb-3">Our Vision</h5>
        <p class="text-white/55 leading-relaxed">To be India's most trusted platform for professional consultancy and mentorship, connecting industries with the right knowledge and expertise to accelerate growth.</p>
      </div>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="py-20" style="background: #0d1530;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold mb-3">Our Expertise</p>
    <h2 class="text-3xl sm:text-4xl font-black mb-4">
      What <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">We Do</span>
    </h2>
    <p class="text-white/55 text-lg mb-14 max-w-2xl mx-auto">ConsultMee offers expert-led solutions across multiple domains to help individuals and organizations grow strategically and sustainably.</p>
    <div class="grid md:grid-cols-3 gap-6">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mx-auto mb-5">
          <i class="bi bi-briefcase-fill text-2xl text-blue-400"></i>
        </div>
        <h5 class="font-bold mb-2">Business Consulting</h5>
        <p class="text-white/50 text-sm leading-relaxed">Strategic guidance to help your business scale efficiently and achieve measurable results.</p>
      </div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center mx-auto mb-5">
          <i class="bi bi-cpu text-2xl text-rose-400"></i>
        </div>
        <h5 class="font-bold mb-2">AI &amp; Technology Consulting</h5>
        <p class="text-white/50 text-sm leading-relaxed">Unlock digital transformation with AI integration, data analytics, and automation solutions.</p>
      </div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300">
        <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mx-auto mb-5">
          <i class="bi bi-cash-coin text-2xl text-amber-400"></i>
        </div>
        <h5 class="font-bold mb-2">Finance &amp; Legal Advisory</h5>
        <p class="text-white/50 text-sm leading-relaxed">Comprehensive support for financial planning, compliance, and investment strategies.</p>
      </div>
    </div>
  </div>
</section>

<!-- INDUSTRIES WE SERVE -->
<section class="py-20" style="background: #080d1c;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold mb-3">Sectors</p>
    <h2 class="text-3xl sm:text-4xl font-black mb-4">
      Industries <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">We Serve</span>
    </h2>
    <p class="text-white/55 text-lg mb-14 max-w-2xl mx-auto">We provide expert consulting services across diverse industries and professional sectors.</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-graph-up-arrow text-3xl text-blue-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Business &amp; Startups</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-bank text-3xl text-emerald-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Finance &amp; Banking</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-cpu text-3xl text-rose-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Technology &amp; AI</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-camera-video text-3xl text-cyan-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Media &amp; Marketing</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-heart-pulse text-3xl text-pink-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Healthcare</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-book text-3xl text-amber-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Education &amp; Training</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-building text-3xl text-violet-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Corporate &amp; Legal</p>
      </div>
      <div class="reveal glass-card bg-white/5 border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 transition-all duration-300">
        <i class="bi bi-globe2 text-3xl text-emerald-400 mb-3 block"></i>
        <p class="font-semibold text-sm">Government &amp; NGOs</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-16" style="background: linear-gradient(135deg, #0d1a3a 0%, #0a1628 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="glass-card bg-white/5 backdrop-blur-xl border border-white/15 rounded-3xl p-10 md:p-14 text-center">
      <h4 class="text-2xl md:text-3xl font-black mb-4">Ready to Collaborate with Industry Experts?</h4>
      <p class="text-white/60 mb-8 max-w-xl mx-auto">Join the ConsultMee network and get access to top consultants and mentors across industries.</p>
      <a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
        Get Started <i class="bi bi-arrow-right"></i>
      </a>
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
