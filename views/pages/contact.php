<style>
  .glass-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; position: relative; overflow: hidden; }
  .glass-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(ellipse at 20% 0%, rgba(37,99,235,0.13), transparent 65%); pointer-events: none; }
  .form-input { width: 100%; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14); border-radius: 12px; padding: 13px 16px; color: #fff; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
  .form-input:focus { border-color: rgba(37,99,235,0.5); }
  .form-input::placeholder { color: rgba(255,255,255,0.3); }
</style>

<!-- HERO -->
<section class="pt-32 pb-16 text-center" style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 60%, #0a1628 100%);">
  <div class="max-w-3xl mx-auto px-4">
    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cobalt/20 border border-cobalt/30 text-accent text-sm font-medium mb-6">
      <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
      Get In Touch
    </span>
    <h1 class="text-4xl sm:text-5xl font-black mb-4">We're Here to <span class="bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Help!</span></h1>
    <p class="text-white/55 text-lg">Get in touch with our team for any inquiries, collaborations, or support.</p>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="py-20" style="background: #080d1c;">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php if (!empty($success)): ?>
    <div class="mb-8 px-6 py-4 rounded-2xl text-center font-semibold text-sm" style="background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.3);color:#34d399;">
      <i class="bi bi-check-circle-fill me-2"></i><?= \ConsultMee\Core\View::escape($success) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="mb-8 px-6 py-4 rounded-2xl text-center font-semibold text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;">
      <i class="bi bi-exclamation-triangle-fill me-2"></i><?= \ConsultMee\Core\View::escape($error) ?>
    </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-5 gap-8 items-start">

      <!-- Info cards -->
      <div class="lg:col-span-2 flex flex-col gap-5">

        <div class="glass-card bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all duration-300">
          <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-geo-alt-fill text-2xl text-blue-400"></i>
          </div>
          <h5 class="font-bold mb-2">Visit Us</h5>
          <address class="text-white/50 text-sm not-italic leading-relaxed">
            2066 2nd Floor, Nazarbaug Palace<br>
            Mandvi, Near Mandvi Gate, Vadodara<br>
            Gujarat, India 390001
          </address>
        </div>

        <div class="glass-card bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all duration-300">
          <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-telephone-fill text-2xl text-emerald-400"></i>
          </div>
          <h5 class="font-bold mb-2">Call Us</h5>
          <a href="tel:918317818107" class="text-white/50 hover:text-white text-sm transition-colors">+91 8317818107</a>
        </div>

        <div class="glass-card bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all duration-300">
          <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-envelope-fill text-2xl text-amber-400"></i>
          </div>
          <h5 class="font-bold mb-2">Email Us</h5>
          <a href="mailto:info@consultmee.in" class="text-white/50 hover:text-white text-sm transition-colors">info@consultmee.in</a>
        </div>

      </div>

      <!-- Contact form -->
      <div class="lg:col-span-3">
        <div class="glass-card bg-white/5 backdrop-blur-xl border border-white/15 rounded-3xl p-8 md:p-10">
          <h4 class="text-xl font-bold mb-2 bg-gradient-to-r from-cobalt to-accent bg-clip-text text-transparent">Send Us a Message</h4>
          <p class="text-white/50 text-sm mb-8">Fill out the form below and we'll respond within 24 hours.</p>

          <form action="/contact" method="POST" class="flex flex-col gap-5">
            <?= csrf_field() ?>

            <div>
              <label for="name" class="block text-sm font-semibold mb-2 text-white/80">Full Name</label>
              <input type="text" class="form-input" id="name" name="name" placeholder="Your full name" required>
            </div>

            <div>
              <label for="email" class="block text-sm font-semibold mb-2 text-white/80">Email Address</label>
              <input type="email" class="form-input" id="email" name="email" placeholder="your@email.com" required>
            </div>

            <div>
              <label for="subject" class="block text-sm font-semibold mb-2 text-white/80">Subject</label>
              <input type="text" class="form-input" id="subject" name="subject" placeholder="How can we help?" required>
            </div>

            <div>
              <label for="message" class="block text-sm font-semibold mb-2 text-white/80">Message</label>
              <textarea class="form-input" id="message" name="message" placeholder="Tell us more about your inquiry..." rows="5" required></textarea>
            </div>

            <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30 mt-2">
              Send Message <i class="bi bi-send ml-2"></i>
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>
