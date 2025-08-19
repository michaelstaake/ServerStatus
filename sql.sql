CREATE TABLE IF NOT EXISTS servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    `order` INT DEFAULT 0,
    display ENUM('public', 'private', 'disabled') DEFAULT 'public',
    status ENUM('up', 'down', 'pending') DEFAULT 'pending',
    uptime DECIMAL(6,3) DEFAULT 100.000,
    monitored TINYINT(1) DEFAULT 0,
    url VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    server INT NOT NULL,
    status ENUM('passed', 'failed') NOT NULL,
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (server) REFERENCES servers(id) ON DELETE CASCADE
);