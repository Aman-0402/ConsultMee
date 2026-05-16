<?php
$states = ["Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chhattisgarh","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal","Andaman and Nicobar Islands","Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Jammu and Kashmir","Ladakh","Lakshadweep","Puducherry"];
$industries = ["Agriculture","Artificial Intelligence","Astronomy","Automobiles and Auto Components","Business & Management","Capital Goods","Chemicals","Construction","Consumer Durables","Consumer Services","Defence","Diversified","Education","Energy","Fast Moving Consumer Goods(FMCG)","Finance","Food, Beverage & Tobacco","Healthcare","Hospitality, Tourism & Leisure","Technology","Law & Order","Media, Entertainment & Publication","Metals & Mining","Oil, Gas & Consumable Fuels","Power","Realty","Services","Telecommunication","Textile","Transport"];
?>
<main class="flex-1 flex items-center justify-center px-4 py-16">
  <div class="w-full max-w-2xl">
    <div class="text-center mb-8">
      <a href="/"><img src="/assets/img/logo.png" alt="ConsultMee" class="h-11 w-auto mx-auto mb-6"></a>
      <h1 class="text-2xl font-black text-slate-900 mb-1">Create Your Account</h1>
      <p class="text-slate-500 text-sm">Join ConsultMee and access expert consultants</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
      <div id="messageBox" class="mb-5 text-center text-sm font-semibold hidden rounded-xl px-4 py-3"></div>

      <form method="post" action="/signup" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Full Name *</label>
          <input type="text" name="full_name" class="form-input" placeholder="Your full name" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Username *</label>
          <input type="text" name="username" class="form-input" placeholder="Choose a username" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Email Address *</label>
          <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Phone Number *</label>
          <input type="tel" name="phone" class="form-input" placeholder="10-digit number" maxlength="10" required>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Area of Interest *</label>
          <select name="interest1" class="form-input" required>
            <option value="">Select primary interest</option>
            <?php foreach ($industries as $ind): ?>
              <option value="<?= \ConsultMee\Core\View::escape($ind) ?>"><?= \ConsultMee\Core\View::escape($ind) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">State *</label>
          <select name="state" class="form-input" required>
            <option value="">Select your state</option>
            <?php foreach ($states as $s): ?>
              <option value="<?= \ConsultMee\Core\View::escape($s) ?>"><?= \ConsultMee\Core\View::escape($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Identity</label>
          <select name="identity" class="form-input">
            <option value="">Select identity</option>
            <option value="Individual">Individual</option>
            <option value="Startup">Startup</option>
            <option value="Business">Business</option>
            <option value="Enterprise">Enterprise</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2 text-slate-700">Password *</label>
          <input type="password" name="password" class="form-input" placeholder="Create password" required>
        </div>
        <div class="sm:col-span-2">
          <button type="submit" name="register" class="w-full py-3.5 rounded-xl bg-cobalt text-white font-semibold hover:bg-blue-700 transition-all shadow-sm">
            Create Account <i class="bi bi-arrow-right ml-1"></i>
          </button>
        </div>
      </form>

      <div class="border-t border-slate-100 mt-5 pt-5 text-center">
        <p class="text-slate-500 text-sm">Already have an account? <a href="/login" class="text-cobalt hover:underline font-semibold">Sign in</a></p>
        <p class="text-slate-400 text-xs mt-2">Are you a consultant? <a href="/consultant/signup" class="text-cobalt hover:underline font-medium">Register here</a></p>
      </div>
    </div>
  </div>
</main>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const msg = urlParams.get('msg'), type = urlParams.get('type');
  if (msg) {
    const box = document.getElementById('messageBox');
    box.classList.remove('hidden');
    box.textContent = decodeURIComponent(msg);
    if (type === 'error') { box.style.cssText = 'color:#dc2626;background:#fef2f2;border:1px solid #fecaca;padding:12px 16px;border-radius:10px;'; }
    else { box.style.cssText = 'color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;padding:12px 16px;border-radius:10px;'; }
  }
</script>
