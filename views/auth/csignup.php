<?php
$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];
$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
?>
<main class="flex-1 flex items-center justify-center px-4 pt-24 pb-16">
  <div class="w-full max-w-2xl">
    <div class="text-center mb-8">
      <img src="/assets/img/logo.png" alt="ConsultMee" class="h-12 w-auto mx-auto mb-5">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-medium mb-4">
        <i class="bi bi-briefcase-fill"></i> Consultant Portal
      </div>
      <h1 class="text-2xl font-black mb-1">Register as Consultant</h1>
      <p class="text-white/50 text-sm">Join ConsultMee and start offering your expertise</p>
    </div>

    <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">
      <div id="messageBox" class="mb-4 text-center text-sm font-medium hidden rounded-xl px-4 py-3"></div>

      <form method="post" action="/consultant/signup" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <?= csrf_field() ?>

        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Full Name *</label>
          <input type="text" name="full_name" class="form-input" placeholder="Your full name" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Username *</label>
          <input type="text" name="username" class="form-input" placeholder="Choose a username" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Email Address *</label>
          <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Phone Number *</label>
          <input type="tel" name="phone" class="form-input" placeholder="10-digit number" maxlength="10" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Area of Expertise *</label>
          <select name="industry" class="form-input" required>
            <option value="">Select your expertise</option>
            <?php foreach ($industries as $ind): ?>
              <option value="<?= \ConsultMee\Core\View::escape($ind) ?>"><?= \ConsultMee\Core\View::escape($ind) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">State *</label>
          <select name="State" class="form-input" required>
            <option value="">Select your state</option>
            <?php foreach ($states as $s): ?>
              <option value="<?= \ConsultMee\Core\View::escape($s) ?>"><?= \ConsultMee\Core\View::escape($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Experience *</label>
          <input type="text" name="experience" class="form-input" placeholder="e.g. 5 years" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Identity</label>
          <select name="identity" class="form-input">
            <option value="">Select identity</option>
            <option value="Chartered Accountant">Chartered Accountant</option>
            <option value="Lawyer">Lawyer</option>
            <option value="Doctor">Doctor</option>
            <option value="Financial Advisor">Financial Advisor</option>
            <option value="Business Consultant">Business Consultant</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="block text-sm font-semibold mb-2 text-white/80">Bio *</label>
          <textarea name="bio" rows="3" class="form-input" placeholder="Tell clients about your expertise..." required></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Password *</label>
          <input type="password" name="password" class="form-input" placeholder="Create password" required>
        </div>
        <div class="flex items-end">
          <button type="submit" name="register" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
            Register <i class="bi bi-arrow-right ml-1"></i>
          </button>
        </div>
      </form>

      <p class="text-center text-white/50 text-sm mt-5">
        Already registered? <a href="/consultant/login" class="text-accent hover:text-white font-medium transition-colors">Login</a>
      </p>
    </div>
  </div>
</main>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get('msg'), type = urlParams.get('type');
  if (msg) {
    const box = document.getElementById('messageBox');
    box.classList.remove('hidden');
    box.innerHTML = decodeURIComponent(msg);
    box.style.color = type === 'error' ? '#f87171' : '#34d399';
    box.style.background = type === 'error' ? 'rgba(239,68,68,0.1)' : 'rgba(52,211,153,0.1)';
    box.style.padding = '10px 16px';
    box.style.borderRadius = '10px';
    box.style.border = type === 'error' ? '1px solid rgba(239,68,68,0.3)' : '1px solid rgba(52,211,153,0.3)';
  }
</script>
