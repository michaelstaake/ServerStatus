<?php
// View helpers for the Napa AI theme.

if (!function_exists('ss_e')) {
    function ss_e($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('ss_asset')) {
    // Asset URL with a cache-busting version from the file's mtime.
    function ss_asset($path) {
        $rel = ltrim($path, '/');
        $file = dirname(__DIR__) . '/' . $rel;
        return '/' . $rel . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}

if (!function_exists('ss_uptime')) {
    // Formats an uptime percentage the same way the original views did.
    function ss_uptime($uptime_val) {
        global $min_uptime;
        if ($uptime_val === null || $uptime_val === '') {
            return 'N/A';
        }
        if (isset($min_uptime) && $min_uptime !== null && $uptime_val < $min_uptime) {
            return '&lt;' . rtrim(rtrim(number_format($min_uptime, 3), '0'), '.') . '%';
        }
        if ($uptime_val == 100) {
            return '100%';
        }
        return rtrim(rtrim(number_format($uptime_val, 3), '0'), '.') . '%';
    }
}

if (!function_exists('ss_status')) {
    // Label and CSS state for a server status.
    function ss_status($status) {
        switch ($status) {
            case 'up':
                return ['label' => 'Operational', 'state' => 'ok'];
            case 'down':
                return ['label' => 'Down', 'state' => 'bad'];
            default:
                return ['label' => 'Checking', 'state' => 'pending'];
        }
    }
}

if (!function_exists('ss_status_icon')) {
    // One shape per state, so status never relies on color alone.
    function ss_status_icon($state) {
        $paths = [
            'ok' => '<path d="M3.2 8.2 6.4 11.4 12.8 5" />',
            'bad' => '<path d="M4.5 4.5l7 7" /><path d="M11.5 4.5l-7 7" />',
            'warn' => '<path d="M8 3.25v6.25" /><path d="M8 12.25h.01" />',
            'pending' => '<path d="M8 4.5V8l2.5 1.5" /><circle cx="8" cy="8" r="5.25" />',
        ];
        $svg = $paths[$state] ?? $paths['pending'];
        return '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $svg . '</svg>';
    }
}

if (!function_exists('ss_check_time')) {
    // Formats a UTC check timestamp in the site timezone.
    // Deducts 5 minutes like the original app, in case server time is off.
    function ss_check_time($utc_timestamp) {
        global $site_timezone;
        if (empty($utc_timestamp)) {
            return null;
        }
        $tz = !empty($site_timezone) ? $site_timezone : 'America/Los_Angeles';
        $dt = new DateTime($utc_timestamp, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone($tz));
        $dt->modify('-5 minutes');
        return $dt->format('F j, Y \a\t g:i A T');
    }
}
