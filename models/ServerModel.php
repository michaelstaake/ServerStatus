<?php

require_once __DIR__ . '/../Database.php';

class ServerModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all public servers ordered by display order.
     */
    public function getPublicServers() {
        $stmt = $this->db->prepare("SELECT * FROM servers WHERE display = 'public' ORDER BY `order` ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a server by slug.
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM servers WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all servers.
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM servers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a server by ID.
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM servers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update server status.
     */
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE servers SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Update server uptime.
     */
    public function updateUptime($id, $uptime) {
        $stmt = $this->db->prepare("UPDATE servers SET uptime = :uptime WHERE id = :id");
        $stmt->execute(['uptime' => $uptime, 'id' => $id]);
    }
}

?>