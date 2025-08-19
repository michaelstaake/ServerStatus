
<?php
require_once 'config.php';

// Block browser access unless dev_mode is enabled
if (php_sapi_name() !== 'cli' && !$dev_mode) {
    http_response_code(403);
    $title = 'Forbidden';
    $content_file = 'views/error_403.php';
    require 'views/layout.php';
    exit;
}

// Set error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Include necessary files
require_once 'Database.php';
require_once 'models/ServerModel.php';
require_once 'models/CheckModel.php';
require_once 'vendors/PHPMailer/PHPMailer.php';
require_once 'vendors/PHPMailer/SMTP.php';
require_once 'vendors/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Initialize models

$serverModel = new ServerModel();
$checkModel = new CheckModel();

// Remove checks for servers that no longer exist
$db = Database::getInstance();
$db->query("DELETE FROM checks WHERE server NOT IN (SELECT id FROM servers)");

// Get all servers
$servers = $serverModel->getAll();

foreach ($servers as $server) {
    $id = $server['id'];
    $url = $server['url'];
    $old_status = $server['status'];
    $monitored = $server['monitored'];
    $slug = $server['slug'];

    // Perform the check using cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $status = ($code === 200 && empty($error)) ? 'passed' : 'failed';

    // Insert the check
    $checkModel->insert($id, $status);

    // Calculate new status
    $last_two = $checkModel->getLastChecks($id, 2);
    if (count($last_two) < 2) {
        $new_status = 'pending';
    } elseif ($last_two[0]['status'] === 'failed' && $last_two[1]['status'] === 'failed') {
        $new_status = 'down';
    } else {
        $new_status = 'up';
    }

    // Send email if monitored and status changed
    if ($monitored && $new_status !== $old_status) {
        $mailer = new PHPMailer(true);
        try {
            $mailer->isSMTP();
            $mailer->Host = $email['smtp_host'];
            $mailer->SMTPAuth = true;
            $mailer->Username = $email['user'];
            $mailer->Password = $email['pass'];
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = $email['port'];
            $mailer->setFrom($email['from']);
            $mailer->addAddress($email['to']);
            $mailer->isHTML(true);

            $server_url = rtrim($baseURL, '/') . '/server/' . $slug;

            if ($new_status === 'down') {
                $mailer->Subject = $server['name'] . ' is Down';
                $mailer->Body = 'The server ' . $server['name'] . ' (' . $url . ') is down.<br><a href="' . $server_url . '">View server status</a>';
            } elseif ($new_status === 'up' && $old_status === 'down') {
                $mailer->Subject = $server['name'] . ' is Back Up';
                $mailer->Body = 'The server ' . $server['name'] . ' (' . $url . ') is back up.<br><a href="' . $server_url . '">View server status</a>';
            }

            $mailer->send();
        } catch (Exception $e) {
            error_log("Mailer Error for server $id: " . $mailer->ErrorInfo);
        }
    }

    // Update status
    $serverModel->updateStatus($id, $new_status);

    // Calculate uptime
    $checks = $checkModel->getChecksLast30Days($id);
    $total = count($checks);
    $down_count = 0;
    if ($total > 0) {
        $i = 0;
        while ($i < $total) {
            if ($checks[$i]['status'] === 'passed') {
                $i++;
                continue;
            }
            $j = $i;
            while ($j < $total && $checks[$j]['status'] === 'failed') {
                $j++;
            }
            $length = $j - $i;
            if ($length >= 2) {
                $down_count += $length;
            }
            $i = $j;
        }
    }
    $up_count = $total - $down_count;
    $uptime = ($total > 0) ? round(($up_count / $total) * 100, 3) : 100.000;

    // Update uptime
    $serverModel->updateUptime($id, $uptime);
}

?>