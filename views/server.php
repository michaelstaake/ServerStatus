<?php
$st = ss_status($server['status']);
$ss_last = ss_check_time($last_check_time ?? null);

// Uptime for each of the last 12 months
$monthly_uptime = [];
$now = new DateTime();
$now->modify('first day of this month');
for ($i = 0; $i < 12; $i++) {
    $month = clone $now;
    $month->modify("-{$i} months");
    $start = $month->format('Y-m-01 00:00:00');
    $end = $month->format('Y-m-t 23:59:59');
    $stmt = Database::getInstance()->prepare("SELECT status FROM checks WHERE server = :server AND `timestamp` BETWEEN :start AND :end");
    $stmt->execute(['server' => $server['id'], 'start' => $start, 'end' => $end]);
    $checks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($checks);
    $up = 0;
    foreach ($checks as $check) {
        if ($check['status'] === 'passed') $up++;
    }
    $monthly_uptime[] = [
        'label' => $month->format('F Y'),
        'uptime' => ($total > 0) ? round(($up / $total) * 100, 3) : null,
    ];
}
?>
<section class="page-head">
  <div class="wrap">
    <nav class="crumbs" aria-label="Breadcrumb">
      <a href="/">Service status</a>
      <span aria-hidden="true">/</span>
      <span aria-current="page"><?php echo ss_e($server['name']); ?></span>
    </nav>
    <h1 class="page-head__title"><?php echo ss_e($server['name']); ?></h1>
    <?php if (!empty($server['description'])): ?>
      <p class="lead"><?php echo ss_e($server['description']); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <ul class="tiles">
      <li class="tile tile--<?php echo ss_e($st['state']); ?>">
        <p class="tile__label">Status</p>
        <p class="tile__value tile__value--status">
          <span class="status-icon status-icon--<?php echo ss_e($st['state']); ?>"><?php echo ss_status_icon($st['state']); ?></span>
          <?php echo ss_e($st['label']); ?>
        </p>
        <p class="tile__note"><?php echo $ss_last ? 'Last check ' . ss_e($ss_last) : 'Not checked yet'; ?></p>
      </li>
      <li class="tile">
        <p class="tile__label">Uptime</p>
        <p class="tile__value"><?php echo ss_uptime($server['uptime']); ?></p>
        <p class="tile__note">Last 30 days</p>
      </li>
    </ul>

    <h2 class="h-section">Uptime by month</h2>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Month</th>
            <th scope="col" class="num">Uptime</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($monthly_uptime as $month): ?>
          <tr>
            <td><?php echo ss_e($month['label']); ?></td>
            <?php if ($month['uptime'] === null): ?>
              <td class="num muted">No data</td>
            <?php else: ?>
              <td class="num<?php echo $month['uptime'] >= 99.9 ? ' is-good' : ''; ?>"><?php echo ss_uptime($month['uptime']); ?></td>
            <?php endif; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p class="footnote">Monthly uptime counts every check in the month. The 30-day figure only counts two or more failed checks in a row as downtime.</p>
  </div>
</section>

<?php require __DIR__ . '/partials/support.php'; ?>
