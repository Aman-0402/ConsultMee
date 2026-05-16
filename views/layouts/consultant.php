<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= \ConsultMee\Core\View::escape($title ?? 'Consultant Dashboard | ConsultMee') ?></title>
  <meta name="csrf-token" content="<?= csrf_token() ?>">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { cobalt: '#1c4d8d', accent: '#4988c4', navy: '#0f2854', pale: '#bde8f5' },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
  <link rel="icon" href="/favicon.ico">
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: #f1f5f9; min-height: 100vh; color: #0f172a; }
    .dash-sidebar { background: #ffffff; border-right: 1px solid #e2e8f0; }
    .dash-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .dash-nav-link { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; color:#475569; font-size:0.875rem; font-weight:500; transition:all 0.15s; text-decoration:none; }
    .dash-nav-link:hover, .dash-nav-link.active { background:#eff6ff; color:#2563eb; }
    .badge-status { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:600; }
  </style>
</head>
<body>
  <?= $content ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/cd.js"></script>
</body>
</html>
