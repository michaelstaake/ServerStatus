<nav class="mb-2 text-sm text-gray-600">
    <a href="<?php echo $baseURL; ?>" class="hover:underline text-blue-600"><?php echo $display_name; ?></a>
    &gt; <span class="text-gray-800 font-semibold"><?php echo $server['name']; ?></span>
</nav>
<h2 class="text-xl font-semibold mb-1"><?php echo $server['name']; ?></h2>

<?php if (!empty($server['description'])): ?>
    <p class="mb-4 text-gray-700 italic"><?php echo $server['description']; ?></p>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="bg-white p-4 rounded shadow flex flex-col justify-center items-center">
        <h3 class="text-lg font-semibold mb-2">Status</h3>
        <span class="text-2xl font-bold <?php echo $server['status'] === 'up' ? 'text-green-600' : ($server['status'] === 'down' ? 'text-red-600' : 'text-yellow-600'); ?>"><?php echo ucfirst($server['status']); ?></span>
        <?php
        global $site_timezone;
        global $dev_mode;
        if (!empty($last_check_time)) {
            $tz = !empty($site_timezone) ? $site_timezone : 'America/New_York';
            $dt = new DateTime($last_check_time, new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone($tz));
            $dt->modify('-5 minutes');
            echo '<span class="text-xs text-gray-500 mt-2">Last check: ' . $dt->format('M j, Y H:i') . ' (' . htmlspecialchars($tz) . ')';
            echo '</span>';
        }
        ?>
    </div>
    <div class="bg-white p-4 rounded shadow flex flex-col justify-center items-center">
        <h3 class="text-lg font-semibold mb-2">Uptime</h3>
        <span class="text-2xl font-bold">
            <?php
            global $min_uptime;
            $uptime_val = $server['uptime'];
            if (isset($min_uptime) && $min_uptime !== null && $uptime_val < $min_uptime) {
                echo '&lt;' . rtrim(rtrim(number_format($min_uptime, 3), '0'), '.') . '%';
            } elseif ($uptime_val == 100) {
                echo '100%';
            } else {
                echo rtrim(rtrim(number_format($uptime_val, 3), '0'), '.') . '%';
            }
            ?>
        </span>
    <span class="text-xs text-gray-500 mt-2">Last 30 days</span>
    </div>
</div>

<div class="bg-white p-4 rounded shadow mb-4">
    <h3 class="text-lg font-semibold mb-2">Uptime by Month (Last 12 Months)</h3>
    <table class="w-full border-collapse mb-2">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2">Month</th>
                <th class="p-2">Uptime</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Calculate uptime for each of the last 12 months
            $monthly_uptime = [];
            $now = new DateTime();
            for ($i = 0; $i < 12; $i++) {
                $month = clone $now;
                $month->modify("-{$i} months");
                $start = $month->format('Y-m-01 00:00:00');
                $end = $month->format('Y-m-t 23:59:59');
                // Get checks for this month
                $stmt = Database::getInstance()->prepare("SELECT status FROM checks WHERE server = :server AND `timestamp` BETWEEN :start AND :end");
                $stmt->execute(['server' => $server['id'], 'start' => $start, 'end' => $end]);
                $checks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total = count($checks);
                $up = 0;
                foreach ($checks as $check) {
                    if ($check['status'] === 'passed') $up++;
                }
                $uptime = ($total > 0) ? round(($up / $total) * 100, 3) : null;
                $monthly_uptime[] = [
                    'label' => $month->format('F Y'),
                    'uptime' => $uptime,
                ];
            }
            global $min_uptime;
            foreach ($monthly_uptime as $month) {
                echo '<tr>';
                echo '<td class="p-2 border">' . htmlspecialchars($month['label']) . '</td>';
                if ($month['uptime'] === null) {
                    echo '<td class="p-2 border text-gray-500">N/A</td>';
                } else {
                    $uptime_val = $month['uptime'];
                    if (isset($min_uptime) && $min_uptime !== null && $uptime_val < $min_uptime) {
                        $uptime_display = '&lt;' . rtrim(rtrim(number_format($min_uptime, 3), '0'), '.') . '%';
                    } elseif ($uptime_val == 100) {
                        $uptime_display = '100%';
                    } else {
                        $uptime_display = rtrim(rtrim(number_format($uptime_val, 3), '0'), '.') . '%';
                    }
                    $class = ($uptime_val >= 99.9) ? 'text-green-600 font-bold' : '';
                    echo '<td class="p-2 border ' . $class . '">' . $uptime_display . '</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php global $support_section, $support_link; ?>
<?php if ($support_section && !empty($support_link)): ?>
<div class="bg-blue-50 p-4 rounded shadow mt-6">
    <h3 class="text-lg font-semibold mb-2 text-blue-800">Need help?</h3>
    <p class="mb-4 text-gray-700">If you're having issues with your service, please contact us.</p>
    <a href="<?php echo htmlspecialchars($support_link); ?>" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">Contact Support</a>
</div>
<?php endif; ?>