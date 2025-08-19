<?php

require_once 'models/ServerModel.php';
require_once 'models/CheckModel.php';

class ServerController {
    public function show($slug) {
    global $display_name, $baseURL, $footer_content;
    $serverModel = new ServerModel();
    $server = $serverModel->getBySlug($slug);

        if (!$server) {
            http_response_code(404);
            $title = 'Not Found';
            $content_file = 'views/error_404.php';
            require 'views/layout.php';
            return;
        }

        if ($server['display'] === 'disabled') {
            http_response_code(403);
            $title = 'Forbidden';
            $content_file = 'views/error_403.php';
            require 'views/layout.php';
            return;
        }

    $checkModel = new CheckModel();
    $recent_checks = $checkModel->getLastChecks($server['id'], 10);
    // Find last check for current status, or fallback to most recent check
    $last_check_time = null;
    foreach ($recent_checks as $check) {
        if (($server['status'] === 'up' && $check['status'] === 'passed') ||
            ($server['status'] === 'down' && $check['status'] === 'failed')) {
            $last_check_time = $check['timestamp'];
            break;
        }
    }
    if (!$last_check_time && !empty($recent_checks)) {
        $last_check_time = $recent_checks[0]['timestamp'];
    }
    $title = $server['name'] . ' - ' . $display_name;
    $content_file = 'views/server.php';
    // Pass $last_check_time to view
    require 'views/layout.php';
    }
}

?>