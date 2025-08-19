<h2 class="text-xl font-semibold">Server Status</h2>
<?php global $how_often; ?>
<p class="mb-6 text-gray-600">
    Server status is automatically checked every <?php echo htmlspecialchars($how_often); ?>, so this information may be delayed slightly, and percentages may be rounded. Uptime is calculated based on the last 30 days.
</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($servers as $server): ?>
        <div class="bg-white p-4 rounded shadow <?php echo $server['status'] === 'up' ? 'border-green-500' : ($server['status'] === 'down' ? 'border-red-500' : 'border-yellow-500'); ?> border-l-4">
            <h3 class="text-lg font-bold"><a href="/server/<?php echo $server['slug']; ?>" class="text-blue-600 hover:underline"><?php echo $server['name']; ?></a></h3>
            <?php if (!empty($server['description'])): ?>
                <p class="text-gray-700 italic mb-2"><?php echo $server['description']; ?></p>
            <?php endif; ?>
            <p>Status: <span class="<?php echo $server['status'] === 'up' ? 'text-green-600' : ($server['status'] === 'down' ? 'text-red-600' : 'text-yellow-600'); ?>"><?php echo ucfirst($server['status']); ?></span></p>
            <p>Uptime: <?php
                global $min_uptime;
                $uptime_val = $server['uptime'];
                if (isset($min_uptime) && $min_uptime !== null && $uptime_val < $min_uptime) {
                    echo '&lt;' . rtrim(rtrim(number_format($min_uptime, 3), '0'), '.') . '%';
                } elseif ($uptime_val == 100) {
                    echo '100%';
                } else {
                    echo rtrim(rtrim(number_format($uptime_val, 3), '0'), '.') . '%';
                }
            ?></p>
        </div>
    <?php endforeach; ?>
</div>