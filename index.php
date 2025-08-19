<?php

require_once 'config.php';

// Set error handling based on dev mode
if ($dev_mode) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Check system requirements
$requirements_met = true;
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    $requirements_met = false;
}
if (!extension_loaded('pdo_mysql') || !extension_loaded('curl')) {
    $requirements_met = false;
}

if (!$requirements_met) {
    http_response_code(503);
    $title = 'System Requirements Not Met';
    $content_file = 'views/error_requirements.php';
    require 'views/layout.php';
    exit;
}

if ($maintenance) {
    http_response_code(503);
    $title = 'Maintenance Mode';
    $content_file = 'views/error_maintenance.php';
    require 'views/layout.php';
    exit;
}

// Include necessary files
require_once 'Database.php';
require_once 'models/ServerModel.php';
require_once 'models/CheckModel.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/ServerController.php';

// Parse the route
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$parts = explode('/', $uri);

if (empty($uri)) {
    $controller = new HomeController();
    $controller->index();
} elseif (count($parts) === 2 && $parts[0] === 'server') {
    $slug = $parts[1];
    $controller = new ServerController();
    $controller->show($slug);
} else {
    http_response_code(404);
    $title = 'Not Found';
    $content_file = 'views/error_404.php';
    require 'views/layout.php';
}

?>