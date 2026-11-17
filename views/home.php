<?php
global $how_often;
$ss_counts = ['ok' => 0, 'bad' => 0, 'pending' => 0];
$ss_latest = null;
foreach ($servers as $s) {
    $ss_counts[ss_status($s['status'])['state']]++;
    if (!empty($s['last_check']) && ($ss_latest === null || $s['last_check'] > $ss_latest)) {
        $ss_latest = $s['last_check'];
    }
}
$ss_total = count($servers);
if ($ss_total === 0) {
    $ss_overall = ['state' => 'pending', 'title' => 'No services listed yet', 'detail' => 'Services will show here once they are added.'];
} elseif ($ss_counts['bad'] === $ss_total) {
    $ss_overall = ['state' => 'bad', 'title' => 'Major outage', 'detail' => 'Our services are down. We are working on it.'];
} elseif ($ss_counts['bad'] > 0) {
    $ss_overall = ['state' => 'warn', 'title' => 'Some services are down', 'detail' => 'We are working on it. Services not listed as down are running normally.'];
} elseif ($ss_counts['pending'] > 0) {
    $ss_overall = ['state' => 'pending', 'title' => 'Checking services', 'detail' => 'Some services were just added and are still being checked.'];
} else {
    $ss_overall = ['state' => 'ok', 'title' => 'All systems operational', 'detail' => 'All Napa AI websites and services are running normally.'];
}
$ss_latest_text = ss_check_time($ss_latest);
?>
<section class="page-head">
  <div class="wrap">
    <h1 class="page-head__title">Service status</h1>
    <p class="lead">Current availability of Napa AI websites and services.</p>
    <?php if ($ss_latest_text): ?>
      <p class="page-head__meta">Last checked <?php echo ss_e($ss_latest_text); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="overall overall--<?php echo ss_e($ss_overall['state']); ?>" role="status">
      <span class="status-icon status-icon--lg status-icon--<?php echo ss_e($ss_overall['state']); ?>"><?php echo ss_status_icon($ss_overall['state']); ?></span>
      <div class="overall__body">
        <p class="overall__title"><?php echo ss_e($ss_overall['title']); ?></p>
        <p class="overall__text"><?php echo ss_e($ss_overall['detail']); ?></p>
      </div>
    </div>

    <?php if ($ss_total > 0): ?>
    <ul class="services" aria-label="Services">
      <?php foreach ($servers as $server): $st = ss_status($server['status']); ?>
        <li class="service service--<?php echo ss_e($st['state']); ?>">
          <span class="status-icon status-icon--<?php echo ss_e($st['state']); ?>"><?php echo ss_status_icon($st['state']); ?></span>
          <div class="service__body">
            <a class="service__name" href="/server/<?php echo rawurlencode($server['slug']); ?>"><?php echo ss_e($server['name']); ?></a>
            <?php if (!empty($server['description'])): ?>
              <p class="service__desc"><?php echo ss_e($server['description']); ?></p>
            <?php endif; ?>
          </div>
          <div class="service__meta">
            <span class="service__status service__status--<?php echo ss_e($st['state']); ?>"><?php echo ss_e($st['label']); ?></span>
            <span class="service__uptime"><?php echo ss_uptime($server['uptime']); ?> uptime</span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <p class="footnote">
      Services are checked automatically <?php echo ss_e($how_often); ?>, so this page may be slightly behind, and percentages may be rounded. Uptime covers the last 30 days.
    </p>
  </div>
</section>

<?php require __DIR__ . '/partials/support.php'; ?>
