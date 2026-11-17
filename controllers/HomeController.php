
<?php
require_once __DIR__ . '/../config.php';
require_once 'models/ServerModel.php';
require_once 'models/CheckModel.php';

class HomeController {
    public function index() {
    global $display_name, $baseURL, $footer_content;
    $serverModel = new ServerModel();
    $servers = $serverModel->getPublicServers();
    $checkModel = new CheckModel();
    foreach ($servers as &$server) {
        $last = $checkModel->getLastChecks($server['id'], 1);
        $server['last_check'] = $last ? $last[0]['timestamp'] : null;
    }
    unset($server);
    $title = $display_name;
    $content_file = 'views/home.php';
    require 'views/layout.php';
    }
}
