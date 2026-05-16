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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="/favicon.ico">
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: linear-gradient(135deg, #0a0f1e 0%, #0d1a3a 50%, #0a1628 100%); min-height: 100vh; }
  </style>
</head>
<body class="text-white">
  <?php \ConsultMee\Core\View::partial('navbar'); ?>
  <main>
    <?= $content ?>
  </main>
  <?php \ConsultMee\Core\View::partial('footer'); ?>
</body>
</html>
