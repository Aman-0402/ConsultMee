<style>
  .form-input { width:100%; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; color:#0f172a; font-size:0.875rem; outline:none; transition:border-color 0.2s, box-shadow 0.2s; }
  .form-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); background:#fff; }
  .form-input::placeholder { color:#94a3b8; }
</style>

<!-- HERO -->
<section class="pt-28 pb-14 section-white border-b border-slate-100 text-center">
  <div class="max-w-3xl mx-auto px-4">
    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-cobalt text-xs font-semibold mb-6">
      <span class="w-1.5 h-1.5 rounded-full bg-cobalt animate-pulse"></span>
      Get In Touch
    </span>
    <h1 class="text-4xl sm:text-5xl font-black text-slate-900 mb-4">We're Here to Help</h1>
    <p class="text-slate-500 text-lg">Reach out for any inquiries, collaborations, or support.</p>
  </div>
</section>

<!-- CONTACT SECTION -->
<section class="section-gray py-20">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php if (!empty($success)): ?>
    <div class="mb-8 px-6 py-4 rounded-xl text-center font-semibold text-sm bg-emerald-50 border border-emerald-200 text-emerald-700">
      <i class="bi bi-check-circle-fill me-2"></i><?= \ConsultMee\Core\View::escape($success) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="mb-8 px-6 py-4 rounded-xl text-center font-semibold text-sm bg-red-50 border border-red-200 text-red-600">
      <i class="bi bi-exclamation-triangle-fill me-2"></i><?= \ConsultMee\Core\View::escape($error) ?>
    </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-5 gap-8 items-start">

      <!-- Info cards -->
      <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="pro-card p-6 text-center hover:border-cobalt transition-colors">
          <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-geo-alt-fill text-2xl text-cobalt"></i>
          </div>
          <h5 class="font-bold text-slate-900 mb-2">Visit Us</h5>
          <address class="text-slate-500 text-sm not-italic leading-relaxed">
            2066 2nd Floor, Nazarbaug Palace<br>
            Mandvi, Near Mandvi Gate<br>
            Vadodara, Gujarat 390001
          </address>
        </div>
        <div class="pro-card p-6 text-center hover:border-cobalt transition-colors">
          <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-telephone-fill text-2xl text-emerald-600"></i>
          </div>
          <h5 class="font-bold text-slate-900 mb-2">Call Us</h5>
          <a href="tel:918317818107" class="text-slate-500 hover:text-cobalt text-sm transition-colors font-medium">+91 8317818107</a>
        </div>
        <div class="pro-card p-6 text-center hover:border-cobalt transition-colors">
          <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-envelope-fill text-2xl text-amber-500"></i>
          </div>
          <h5 class="font-bold text-slate-900 mb-2">Email Us</h5>
          <a href="mailto:info@consultmee.in" class="text-slate-500 hover:text-cobalt text-sm transition-colors font-medium">info@consultmee.in</a>
        </div>
      </div>

      <!-- Contact form -->
      <div class="lg:col-span-3">
        <div class="pro-card p-8 md:p-10">
          <h4 class="text-xl font-bold text-slate-900 mb-1">Send Us a Message</h4>
          <p class="text-slate-400 text-sm mb-7">Fill out the form and we'll respond within 24 hours.</p>

          <form action="/contact" method="POST" class="flex flex-col gap-5">
            <?= csrf_field() ?>
            <div>
              <label for="name" class="block text-sm font-semibold mb-2 text-slate-700">Full Name</label>
              <input type="text" class="form-input" id="name" name="name" placeholder="Your full name" required>
            </div>
            <div>
              <label for="email" class="block text-sm font-semibold mb-2 text-slate-700">Email Address</label>
              <input type="email" class="form-input" id="email" name="email" placeholder="your@email.com" required>
            </div>
            <div>
              <label for="subject" class="block text-sm font-semibold mb-2 text-slate-700">Subject</label>
              <input type="text" class="form-input" id="subject" name="subject" placeholder="How can we help?" required>
            </div>
            <div>
              <label for="message" class="block text-sm font-semibold mb-2 text-slate-700">Message</label>
              <textarea class="form-input" id="message" name="message" placeholder="Tell us more about your inquiry..." rows="5" required></textarea>
            </div>
            <button type="submit" class="w-full py-4 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 mt-1">
              Send Message <i class="bi bi-send ml-2"></i>
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>
