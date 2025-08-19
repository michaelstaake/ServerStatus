<?php

require_once __DIR__ . '/../Database.php';

class CheckModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Insert a new check.
     */
    public function insert($serverId, $status) {
        $utc_timestamp = gmdate('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO checks (server, status, `timestamp`) VALUES (:server, :status, :timestamp)");
        $stmt->execute(['server' => $serverId, 'status' => $status, 'timestamp' => $utc_timestamp]);
    }

    /**
     * Get last N checks for a server (ordered descending).
     */
    public function getLastChecks($serverId, $limit) {
        $stmt = $this->db->prepare("SELECT status, `timestamp` FROM checks WHERE server = :server ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':server', $serverId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all checks for a server in the last 30 days (ordered ascending).
     */
    public function getChecksLast30Days($serverId) {
        $stmt = $this->db->prepare("SELECT status, `timestamp` FROM checks WHERE server = :server AND `timestamp` >= NOW() - INTERVAL 30 DAY ORDER BY `timestamp` ASC");
        $stmt->execute(['server' => $serverId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>