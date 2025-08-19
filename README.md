# ServerStatus by Michael Staake

A simple PHP-based server status dashboard for monitoring and displaying uptime and status of multiple servers.

## Installation

1. **Clone or Download the Repository**
   - Place the files on your web server. Subdomains are supported by subfolders are not.

2. **Configuration**
   - Rename `config.example.php` to `config.php`.
   - Edit `config.php` and set your site name, database credentials, email settings, timezone, and other options as needed.

3. **Database Setup**
   - Import the SQL schema from `sql.sql` into your MySQL database. This will create the required tables (`servers` and `checks`).

4. **Add Servers**
   - Use phpMyAdmin or run SQL queries to add servers to the `servers` table.

## Database Schema

### `servers` Table
| Column      | Type      | Description                                                                                 |
|------------ |---------- |--------------------------------------------------------------------------------------------|
| id          | INT       | Auto-increment primary key                                                                  |
| slug        | VARCHAR   | Unique identifier for the server (used in URLs)                                             |
| name        | VARCHAR   | Display name for the server                                                                 |
| description | TEXT      | Description of the server                                                                   |
| order       | INT       | Display order (lower numbers appear first)                                                  |
| display     | ENUM      | 'public', 'private', 'disabled' — controls visibility                                       |
| status      | ENUM      | 'up', 'down', 'pending' — suggest pending for new additions                                 |
| uptime      | DECIMAL   | Uptime percentage (last 30 days, up to 3 decimals)                                          |
| monitored   | TINYINT   | 1 if you want email alerts, 0 if you don't                                                  |
| url         | VARCHAR   | URL to check for status like https://example.com or https://cpanel.example.com:2087         |

## System Requirements
- PHP 8.1 or higher
- MySQL with PDO extension
- cURL extension for PHP
- Web server (Apache recommended; .htaccess provided for clean URLs and HTTPS enforcement)

## Usage
- Configure your servers in the database.
- The cron job (`cron.php`) should be scheduled to run at your desired interval (e.g., every 5 minutes). The frequency in the config file is just to display on the site, but obviously that should match the cron time so that it makes sense.
- The web interface will display server status, uptime, and recent checks.

## Customization
- Everything is configured in `config.php`.
- Possible display options for servers are public, private, and disabled. public show on home and can be accessed via slug. private can be accessed via slug but doesn't display on home page. disabled won't display on home page and accessing the slug will return a 403.

## Notes
- The last checked time on the server page automatically deducts 5 minutes from the actual time of the last check. This is done in case server time isn't quite right. That's very common. In theory, you can just fix this on the server, but if you're running this on shared hosting you may not have access to that.
- ServerStatus just checks for a 200 OK status code. Any other status code or a time out will mean the check is failed.

## Support
For help, set the support section and link in `config.php` or contact Wag Websites via your configured support link.
