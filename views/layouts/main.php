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
          colors: {
            cobalt: '#2563eb',
            accent: '#0ea5e9',
            navy: '#0f172a',
          },
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
          boxShadow: {
            card: '0 1px 3px rgba(0,0,0,0.08), 0 8px 24px rgba(0,0,0,0.06)',
            'card-hover': '0 4px 12px rgba(0,0,0,0.1), 0 16px 40px rgba(37,99,235,0.12)',
          }
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: #f8fafc; color: #0f172a; }
    .section-white { background: #ffffff; }
    .section-gray  { background: #f1f5f9; }
    .section-blue  { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #0ea5e9 100%); }
    .pro-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 8px 24px rgba(0,0,0,0.05); transition: box-shadow 0.2s, transform 0.2s; }
    .pro-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1), 0 16px 40px rgba(37,99,235,0.10); transform: translateY(-3px); }
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.5s ease, transform 0.5s ease; }
    .reveal.visible { opacity: 1; transform: none; }
  </style>
</head>
<body>
  <?php \ConsultMee\Core\View::partial('navbar'); ?>
  <?= $content ?? '' ?>
  <?php \ConsultMee\Core\View::partial('footer'); ?>
</body>
</html>
