<style>
  .glass-card { position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 30% 0%, rgba(37,99,235,0.18), transparent 70%); pointer-events: none; }
  .navbar-scrolled { background: rgba(10,15,30,0.95) !important; box-shadow: 0 4px 30px rgba(0,0,0,0.3); }
  .counter { font-variant-numeric: tabular-nums; }
  .reveal { opacity: 0; transform: translateY(32px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .reveal.show { opacity: 1; transform: translateY(0); }
  .hero-bg { background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%); position: relative; overflow: hidden; }
  .hero-bg::before { content: ''; position: absolute; top: -40%; right: -20%; width: 700px; height: 700px; background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%); pointer-events: none; }
  .hero-bg::after { content: ''; position: absolute; bottom: -20%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(14,165,233,0.10) 0%, transparent 70%); pointer-events: none; }
  .section-dark { background: #080d1c; }
  .section-mid  { background: #0d1530; }
</style>

<!-- HERO -->
<section class="hero-bg min-h-screen flex items-center pt-20 pb-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cobalt/20 border border-cobalt/30 text-accent text-sm font-medium mb-6">
          <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
          On-Demand Expert Network for Business &amp; Tech Decisions
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight mb-6">
          Stop Guessing.<br>
          <span class="bg-gradient-to-r from-cobalt via-primary to-accent bg-clip-text text-transparent">Ask an Expert.</span>
        </h1>
        <p class="text-white/70 text-lg mb-8">Get matched with the right expert within <span class="text-accent font-semibold">24 hours</span></p>
        <div class="flex flex-wrap gap-4 mb-8">
          <a href="/signup" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 text-base">
            Get Started <i class="bi bi-arrow-right"></i>
          </a>
          <a href="/login" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold hover:bg-white/15 transition-all text-base">
            Browse Experts
          </a>
        </div>
        <p class="text-white/40 text-sm mb-10">No commitment &bull; Fast matching &bull; Confidential discussions</p>
        <div class="mb-10">
          <p class="text-white/40 text-xs uppercase tracking-widest mb-3">Professionals from companies like</p>
          <div class="flex flex-wrap gap-3">
            <span class="px-3 py-1 rounded-md bg-white/5 border border-white/10 text-white/60 text-sm">Google</span>
            <span class="px-3 py-1 rounded-md bg-white/5 border border-white/10 text-white/60 text-sm">Infosys</span>
            <span class="px-3 py-1 rounded-md bg-white/5 border border-white/10 text-white/60 text-sm">TCS</span>
            <span class="px-3 py-1 rounded-md bg-white/5 border border-white/10 text-white/60 text-sm">Accenture</span>
            <span class="px-3 py-1 rounded-md bg-white/5 border border-white/10 text-white/60 text-sm">Funded Startups</span>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div class="text-center"><div class="text-2xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">1200+</div><div class="text-white/50 text-xs mt-1">Verified Experts</div></div>
          <div class="text-center border-x border-white/10"><div class="text-2xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">25+</div><div class="text-white/50 text-xs mt-1">Industries</div></div>
          <div class="text-center"><div class="text-2xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">1000+</div><div class="text-white/50 text-xs mt-1">Consultations</div></div>
        </div>
      </div>
      <div class="relative flex flex-col items-center gap-6">
        <canvas id="hero3d" class="absolute inset-0 w-full h-full pointer-events-none opacity-60"></canvas>
        <div class="glass-card relative z-10 w-full max-w-sm bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
          <h4 class="text-xl font-bold mb-6 text-white">How It Works</h4>
          <div class="flex flex-col gap-5">
            <div class="flex items-start gap-4"><span class="flex-shrink-0 w-9 h-9 rounded-xl bg-cobalt/30 border border-cobalt/40 flex items-center justify-center text-accent font-bold text-sm">01</span><p class="text-white/80 pt-1.5 font-medium">Tell us your problem</p></div>
            <div class="flex items-start gap-4"><span class="flex-shrink-0 w-9 h-9 rounded-xl bg-cobalt/30 border border-cobalt/40 flex items-center justify-center text-accent font-bold text-sm">02</span><p class="text-white/80 pt-1.5 font-medium">Get matched with experts</p></div>
            <div class="flex items-start gap-4"><span class="flex-shrink-0 w-9 h-9 rounded-xl bg-cobalt/30 border border-cobalt/40 flex items-center justify-center text-accent font-bold text-sm">03</span><p class="text-white/80 pt-1.5 font-medium">Consult &amp; validate strategy</p></div>
            <div class="flex items-start gap-4"><span class="flex-shrink-0 w-9 h-9 rounded-xl bg-cobalt/30 border border-cobalt/40 flex items-center justify-center text-accent font-bold text-sm">04</span><p class="text-white/80 pt-1.5 font-medium">Execute with clarity</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SOLUTIONS -->
<section class="section-dark py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-14">
      <p class="text-accent text-xs uppercase tracking-widest font-semibold mb-3">Our Solutions</p>
      <h2 class="text-3xl sm:text-4xl font-black mb-4">Expertise That Translates<br><span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Into Real Results</span></h2>
      <p class="text-white/60 text-lg max-w-2xl">ConsultME delivers expert-led solutions designed to help individuals, startups, and enterprises make better decisions and move faster.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 hover:bg-white/8 transition-all duration-300"><div class="w-12 h-12 rounded-xl bg-cobalt/20 border border-cobalt/30 flex items-center justify-center mb-4"><i class="bi bi-bullseye text-accent text-xl"></i></div><h5 class="font-semibold text-white mb-2">Precision Guidance</h5><p class="text-white/50 text-sm leading-relaxed">Tailored expert advice aligned with your goals, industry, and growth stage.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 hover:bg-white/8 transition-all duration-300"><div class="w-12 h-12 rounded-xl bg-cobalt/20 border border-cobalt/30 flex items-center justify-center mb-4"><i class="bi bi-diagram-3 text-accent text-xl"></i></div><h5 class="font-semibold text-white mb-2">End-to-End Consulting</h5><p class="text-white/50 text-sm leading-relaxed">From strategy and planning to execution and optimization handled seamlessly.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 hover:bg-white/8 transition-all duration-300"><div class="w-12 h-12 rounded-xl bg-cobalt/20 border border-cobalt/30 flex items-center justify-center mb-4"><i class="bi bi-people text-accent text-xl"></i></div><h5 class="font-semibold text-white mb-2">Elite Expert Network</h5><p class="text-white/50 text-sm leading-relaxed">Access a curated pool of verified professionals across 50+ domains.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:-translate-y-1.5 hover:bg-white/8 transition-all duration-300"><div class="w-12 h-12 rounded-xl bg-cobalt/20 border border-cobalt/30 flex items-center justify-center mb-4"><i class="bi bi-graph-up-arrow text-accent text-xl"></i></div><h5 class="font-semibold text-white mb-2">Growth Roadmaps</h5><p class="text-white/50 text-sm leading-relaxed">Clear, measurable plans for career acceleration and business scaling.</p></div>
    </div>
    <div id="metrics" class="grid grid-cols-3 gap-6 text-center mb-14">
      <div class="glass-card bg-white/5 border border-white/10 rounded-2xl py-8 px-4"><div class="text-4xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent counter mb-2" data-target="1200" data-suffix="+">0</div><p class="text-white/50 text-sm">Experts</p></div>
      <div class="glass-card bg-white/5 border border-white/10 rounded-2xl py-8 px-4"><div class="text-4xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent counter mb-2" data-target="98" data-suffix="%">0</div><p class="text-white/50 text-sm">Satisfaction</p></div>
      <div class="glass-card bg-white/5 border border-white/10 rounded-2xl py-8 px-4"><div class="text-4xl font-black bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent counter mb-2" data-target="25" data-suffix="+">0</div><p class="text-white/50 text-sm">Industries</p></div>
    </div>
    <div class="text-center"><a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">Speak With an Expert <i class="bi bi-arrow-right"></i></a></div>
  </div>
</section>

<!-- SERVICES -->
<section class="section-mid py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold text-center mb-3">Our Services</p>
    <h2 class="text-3xl sm:text-4xl font-black text-center mb-14">Connect Clients &amp; Consultants</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mb-5"><i class="bi bi-calendar-check text-2xl text-blue-400"></i></div><h5 class="font-bold text-white mb-2">Appointment Booking</h5><p class="text-white/50 text-sm leading-relaxed">Schedule meetings with verified consultants seamlessly through our easy appointment system.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mb-5"><i class="bi bi-kanban text-2xl text-emerald-400"></i></div><h5 class="font-bold text-white mb-2">Project / Task Posting</h5><p class="text-white/50 text-sm leading-relaxed">Post your project requirements and receive proposals from expert consultants instantly.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mb-5"><i class="bi bi-person-lines-fill text-2xl text-amber-400"></i></div><h5 class="font-bold text-white mb-2">Consultant Suggestions</h5><p class="text-white/50 text-sm leading-relaxed">Get AI-driven recommendations to match you with the most suitable industry experts.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center mb-5"><i class="bi bi-currency-rupee text-2xl text-rose-400"></i></div><h5 class="font-bold text-white mb-2">Affordable Pricing</h5><p class="text-white/50 text-sm leading-relaxed">Transparent and budget-friendly pricing models suitable for startups, MSMEs, and enterprises.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-cyan-500/15 border border-cyan-500/25 flex items-center justify-center mb-5"><i class="bi bi-lightning-charge text-2xl text-cyan-400"></i></div><h5 class="font-bold text-white mb-2">One-Click Booking</h5><p class="text-white/50 text-sm leading-relaxed">Confirm and book your consultant instantly with a fast and hassle-free one-click process.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-violet-500/15 border border-violet-500/25 flex items-center justify-center mb-5"><i class="bi bi-globe2 text-2xl text-violet-400"></i></div><h5 class="font-bold text-white mb-2">Multi Industry Expertise</h5><p class="text-white/50 text-sm leading-relaxed">Access professionals across technology, legal, finance, healthcare, media, and more.</p></div>
    </div>
    <div class="text-center mt-12"><a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">Explore All Services <i class="bi bi-arrow-right"></i></a></div>
  </div>
</section>

<!-- PHILOSOPHY -->
<section class="section-dark py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      <p class="text-accent text-xs uppercase tracking-widest font-semibold mb-3">Our Philosophy</p>
      <h2 class="text-3xl sm:text-4xl font-black mb-4">Building Meaningful Connections<br><span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Between Vision &amp; Expertise</span></h2>
      <p class="text-white/60 text-lg max-w-2xl">At ConsultME, we believe access to the right expert at the right time can transform uncertainty into structured growth.</p>
    </div>
    <div class="mb-12"><div class="reveal glass-card max-w-4xl bg-white/5 backdrop-blur-md border-l-4 border-cobalt border border-white/10 rounded-2xl p-8"><p class="text-white/75 text-lg leading-relaxed">We empower individuals, startups, and enterprises by connecting them with verified consultants who convert clarity into action — enabling confident decisions, faster execution, and measurable impact.</p></div></div>
    <div class="grid md:grid-cols-3 gap-6">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-compass-fill text-2xl text-blue-400"></i></div><h5 class="font-semibold text-white mb-3">Clarity Before Complexity</h5><p class="text-white/50 text-sm leading-relaxed">We simplify complex challenges into structured insights, helping you move forward with focus and direction.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-person-badge-fill text-2xl text-emerald-400"></i></div><h5 class="font-semibold text-white mb-3">Experience-Led Guidance</h5><p class="text-white/50 text-sm leading-relaxed">Our consultants bring real-world execution experience — not just theory — ensuring practical, results-driven advice.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-graph-up-arrow text-2xl text-amber-400"></i></div><h5 class="font-semibold text-white mb-3">Outcome-Focused Growth</h5><p class="text-white/50 text-sm leading-relaxed">We measure success through impact — scalable systems, stronger decisions, and sustainable long-term growth.</p></div>
    </div>
    <div class="text-center mt-12"><a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">Connect With an Expert <i class="bi bi-arrow-right"></i></a></div>
  </div>
</section>

<!-- WHY CHOOSE -->
<section class="section-mid py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-accent text-xs uppercase tracking-widest font-semibold text-center mb-3">Why Choose Experts</p>
    <h2 class="text-3xl sm:text-4xl font-black text-center mb-14">Why Should You Hire Experts?</h2>
    <div class="grid md:grid-cols-3 gap-6">
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-person-check-fill text-2xl text-blue-400"></i></div><h5 class="font-bold text-white mb-3">Personalized Solutions</h5><p class="text-white/50 text-sm leading-relaxed">Receive tailored strategies that fit your specific goals, industry, and vision.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-people-fill text-2xl text-emerald-400"></i></div><h5 class="font-bold text-white mb-3">Networking Opportunity</h5><p class="text-white/50 text-sm leading-relaxed">Connect with industry leaders, professionals, and investors to grow your network.</p></div>
      <div class="glass-card reveal bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-7 text-center hover:-translate-y-1.5 transition-all duration-300"><div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mx-auto mb-5"><i class="bi bi-lightbulb-fill text-2xl text-amber-400"></i></div><h5 class="font-bold text-white mb-3">Expert Consultation</h5><p class="text-white/50 text-sm leading-relaxed">Gain deep insights and clarity from top domain experts in every session.</p></div>
    </div>
    <div class="text-center mt-12"><a href="/signup" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">Explore All Services <i class="bi bi-arrow-right"></i></a></div>
  </div>
</section>

<!-- CTA BANNER -->
<section class="py-16" style="background: linear-gradient(135deg, #0d1a3a 0%, #0a1628 100%);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="glass-card bg-white/5 backdrop-blur-xl border border-white/15 rounded-3xl p-10 md:p-14 flex flex-col md:flex-row items-center justify-between gap-8">
      <div>
        <h4 class="text-2xl md:text-3xl font-black text-white mb-3">Need a consulting service?</h4>
        <p class="text-white/60 text-base max-w-xl">Get instant access to verified experts across industries. Your consultation starts without delay — reliable, and tailored to your needs.</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
        <a href="/signup" class="px-7 py-3.5 rounded-full bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-500/30 text-center whitespace-nowrap">Register Now</a>
        <a href="/contact" class="px-7 py-3.5 rounded-full border border-white/25 text-white font-semibold hover:bg-white/10 transition-all text-center whitespace-nowrap">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<!-- Three.js 3D hero -->
<script src="https://unpkg.com/three@0.158.0/build/three.min.js"></script>
<script>
(function(){
  const canvas = document.getElementById("hero3d");
  if (!canvas) return;
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
  camera.position.set(0, 0, 5);
  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setSize(canvas.clientWidth, canvas.clientHeight);
  renderer.setPixelRatio(window.devicePixelRatio);
  renderer.setClearColor(0x000000, 0);
  const light = new THREE.DirectionalLight(0x2563eb, 1.2);
  light.position.set(5, 5, 5);
  scene.add(light);
  const geometry = new THREE.IcosahedronGeometry(1.9, 1);
  const material = new THREE.MeshBasicMaterial({ color: 0x2563eb, wireframe: true });
  const heroObject = new THREE.Mesh(geometry, material);
  scene.add(heroObject);
  function animate() { requestAnimationFrame(animate); heroObject.rotation.y += 0.0015; heroObject.rotation.x += 0.0006; renderer.render(scene, camera); }
  animate();
  window.addEventListener("resize", () => { const w = canvas.clientWidth, h = canvas.clientHeight; renderer.setSize(w, h); camera.aspect = w/h; camera.updateProjectionMatrix(); });
})();
</script>
<script>
window.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('show'); else entry.target.classList.remove('show'); });
  }, { threshold: 0.15, rootMargin: '0px 0px -80px 0px' });
  elements.forEach(el => observer.observe(el));
  const counters = document.querySelectorAll(".counter");
  const metricsSection = document.querySelector("#metrics");
  let started = false;
  function startCounting() {
    counters.forEach(counter => {
      const target = +counter.getAttribute("data-target");
      const suffix = counter.getAttribute("data-suffix") || "";
      const duration = 2000;
      const startTime = performance.now();
      function updateCount(currentTime) { const elapsed = currentTime - startTime; const progress = Math.min(elapsed / duration, 1); counter.innerText = Math.floor(progress * target) + suffix; if (progress < 1) requestAnimationFrame(updateCount); else counter.innerText = target + suffix; }
      requestAnimationFrame(updateCount);
    });
  }
  if (metricsSection) { const metricObserver = new IntersectionObserver(entries => { if (entries[0].isIntersecting && !started) { started = true; startCounting(); } }, { threshold: 0.4 }); metricObserver.observe(metricsSection); }
});
</script>
