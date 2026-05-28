<?php
/**
 * Migration 006: Create Audit Log Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreateAuditLogTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS audit_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                action VARCHAR(100) NOT NULL,
                entity_type VARCHAR(50),
                entity_id INT,
                old_values JSON,
                new_values JSON,
                ip_address VARCHAR(45),
                user_agent VARCHAR(500),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_action (action),
                INDEX idx_created_at (created_at),
                INDEX idx_entity (entity_type, entity_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS audit_log (
                id SERIAL PRIMARY KEY,
                user_id INT REFERENCES users(id) ON DELETE SET NULL,
                action VARCHAR(100) NOT NULL,
                entity_type VARCHAR(50),
                entity_id INT,
                old_values JSONB,
                new_values JSONB,
                ip_address VARCHAR(45),
                user_agent VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        }

        $db->execute($sql);
        echo "✓ Audit log table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS audit_log");
        echo "✓ Audit log table dropped\n";
    }
}
