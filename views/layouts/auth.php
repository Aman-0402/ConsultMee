<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= \ConsultMee\Core\View::escape($title ?? 'ConsultMee') ?></title>

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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: #f1f5f9; }
    .form-input { width:100%; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; color:#0f172a; font-size:0.875rem; outline:none; transition:border-color 0.2s, box-shadow 0.2s; }
    .form-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); background:#fff; }
    .form-input::placeholder { color:#94a3b8; }
    select.form-input { appearance: auto; }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <?= $content ?? '' ?>
</body>
</html>
