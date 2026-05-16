<?php
$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];
$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
?>
<main class="flex-1 flex items-center justify-center px-4 pt-24 pb-16">
  <div class="w-full max-w-2xl">
    <div class="text-center mb-8">
      <img src="/assets/img/logo.png" alt="ConsultMee" class="h-12 w-auto mx-auto mb-5">
      <h1 class="text-2xl font-black mb-1">Create Account</h1>
      <p class="text-white/50 text-sm">Join ConsultMee and connect with expert consultants</p>
    </div>

    <div class="glass-card bg-white/8 backdrop-blur-xl border border-white/15 rounded-3xl p-8 shadow-2xl">
      <div id="messageBox" class="mb-4 text-center text-sm font-medium hidden rounded-xl px-4 py-3"></div>

      <form method="post" action="/signup" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
          <label class="block text-sm font-semibold mb-2 text-white/80">Password *</label>
          <input type="password" name="password" class="form-input" placeholder="Create password" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Confirm Password *</label>
          <input type="password" name="confirm_password" class="form-input" placeholder="Repeat password" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">State *</label>
          <select name="state" class="form-input" required>
            <option value="">Select your state</option>
            <?php foreach ($states as $s): ?>
              <option value="<?= \ConsultMee\Core\View::escape($s) ?>"><?= \ConsultMee\Core\View::escape($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Identity</label>
          <select name="identity" class="form-input">
            <option value="">Select identity</option>
            <option value="Student">Student</option>
            <option value="Professional">Professional</option>
            <option value="Business Owner">Business Owner</option>
            <option value="Entrepreneur">Entrepreneur</option>
            <option value="Freelancer">Freelancer</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Industry Interest 1 *</label>
          <select name="industry1" class="form-input" required>
            <option value="">Select industry</option>
            <?php foreach ($industries as $ind): ?>
              <option value="<?= \ConsultMee\Core\View::escape($ind) ?>"><?= \ConsultMee\Core\View::escape($ind) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-white/80">Industry Interest 2</label>
          <select name="industry2" class="form-input">
            <option value="">Select industry (optional)</option>
            <?php foreach ($industries as $ind): ?>
              <option value="<?= \ConsultMee\Core\View::escape($ind) ?>"><?= \ConsultMee\Core\View::escape($ind) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="block text-sm font-semibold mb-2 text-white/80">Industry Interest 3</label>
          <select name="industry3" class="form-input">
            <option value="">Select industry (optional)</option>
            <?php foreach ($industries as $ind): ?>
              <option value="<?= \ConsultMee\Core\View::escape($ind) ?>"><?= \ConsultMee\Core\View::escape($ind) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="sm:col-span-2">
          <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cobalt to-accent text-white font-semibold hover:-translate-y-0.5 transition-all shadow-xl shadow-blue-500/30">
            Create Account <i class="bi bi-arrow-right ml-1"></i>
          </button>
        </div>
      </form>

      <p class="text-center text-white/50 text-sm mt-5">
        Already have an account? <a href="/login" class="text-accent hover:text-white font-medium transition-colors">Login</a>
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
