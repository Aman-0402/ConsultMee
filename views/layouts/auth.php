<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= \ConsultMee\Core\View::escape($title ?? 'ConsultMee') ?></title>
  <meta name="csrf-token" content="<?= csrf_token() ?>">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { cobalt: '#2563eb', accent: '#0ea5e9' },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%); min-height: 100vh; }
    .glass-card { position: relative; overflow: hidden; }
    .glass-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background: radial-gradient(ellipse at 30% 0%, rgba(37,99,235,0.15), transparent 70%);
      pointer-events: none;
    }
    .form-input {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 12px;
      color: white;
      padding: 13px 16px;
      width: 100%;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      font-size: 0.95rem;
    }
    .form-input:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.22);
    }
    .form-input::placeholder { color: rgba(255,255,255,0.3); }
    select.form-input option { background: #0d1a3a; color: white; }
  </style>
</head>
<body class="text-white flex flex-col min-h-screen">
  <?php \ConsultMee\Core\View::partial('navbar'); ?>
  <?= $content ?>
</body>
</html>
