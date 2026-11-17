<?php
require_once __DIR__ . '/helpers.php';
global $display_name, $site_description;
$ss_site = !empty($display_name) ? $display_name : 'Napa AI Status';
$ss_title = (isset($title) && $title !== '') ? $title : $ss_site;
if ($ss_title !== $ss_site && substr($ss_title, -strlen($ss_site)) !== $ss_site) {
    $ss_title .= ' - ' . $ss_site;
}
$ss_description = !empty($site_description) ? $site_description : 'Current status of Napa AI websites and services.';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<meta name="theme-color" content="#0c0c0e">
<title><?php echo ss_e($ss_title); ?></title>
<meta name="description" content="<?php echo ss_e($ss_description); ?>">
<link rel="preload" href="/assets/fonts/kumbh-sans-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?php echo ss_e(ss_asset('assets/css/napa-status.css')); ?>">
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicon.png">
<link rel="apple-touch-icon" href="/assets/img/favicon.png">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="wrap site-header__inner">
    <a class="brand" href="/" aria-label="<?php echo ss_e($ss_site); ?> home">
      <img src="/assets/img/napa-ai-logo.svg" alt="Napa AI" width="113" height="30">
      <span class="brand__product">Status</span>
    </a>

    <nav class="site-nav" aria-label="Main">
      <ul>
        <li><a href="/" aria-current="page">Status</a></li>
        <li><a href="https://napa-ai.com/">Napa AI</a></li>
        <li><a href="https://portal.napa-ai.com/">Client Login</a></li>
        <li><a href="https://napa-ai.com/contact">Contact</a></li>
      </ul>
    </nav>
  </div>
</header>

<main id="main" tabindex="-1">
<?php require $content_file; ?>
</main>

<footer class="site-footer">
  <div class="wrap site-footer__inner">
    <div class="site-footer__copy">
      <?php if (!empty($footer_content)): ?>
        <?php echo $footer_content; ?>
      <?php else: ?>
      <p>
        Copyright &copy; <?php echo date('Y'); ?> <a href="https://napa-ai.com/">Napa AI</a>.
        <a href="https://napa-ai.com/terms">Terms of service</a>.
        <a href="https://napa-ai.com/privacy">Privacy policy</a>.
      </p>
      <?php endif; ?>
    </div>
  </div>
</footer>
</body>
</html>
