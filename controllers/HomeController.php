
<?php
require_once __DIR__ . '/../config.php';
require_once 'models/ServerModel.php';

class HomeController {
    public function index() {
    global $display_name, $baseURL, $footer_content;
    $serverModel = new ServerModel();
    $servers = $serverModel->getPublicServers();
    $title = $display_name;
    $content_file = 'views/home.php';
    require 'views/layout.php';
    }
}

?>