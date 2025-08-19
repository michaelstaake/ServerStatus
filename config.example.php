<?php

// Database configuration
$db = [
    'host' => 'localhost',
    'user' => 'username',
    'pass' => 'password',
    'name' => 'database'
];

// Email configuration for SMTP alerts
$email = [
    'smtp_host' => 'mail.example.com',
    'user' => 'noreply@example.com',
    'pass' => 'DogeMemesAreCute',
    'port' => 587,
    'from' => 'noreply@example.com',
    'to' => 'admin@example.com' //this determines where the notifications go
];

// Site Name
$display_name = 'Server Status';

// Footer Content
$footer_content = '';

// How often the cron is run (for display only, how often the cron runs is handled on your server)
$how_often = 'every 5 minutes';

// Timezone for displaying times
$site_timezone = 'America/Los_Angeles';
date_default_timezone_set($site_timezone);

// Minimum uptime percentage for display (set to null to disable)
$min_uptime = null; // Example: 99.5

// Base URL for the site (used in email links)
$baseURL = 'https://status.example.com/';

// Support section on server pages
$support_section = false; // true to show, false to hide
$support_link = 'https://example.com/support';

// Development mode toggle
// false: cron can only run from cli, errors are logged only. true: cron can run from browser, errors are displayed and logged.
$dev_mode = false;

// Maintenance mode toggle (true: show maintenance view for all requests)
$maintenance = false;

?>