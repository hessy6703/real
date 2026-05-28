<?php
/**
 * Migration 004: Create Peer Reviews Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreatePeerReviewsTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS peer_reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                employee_id INT NOT NULL,
                reviewer_employee_id INT NOT NULL,
                evaluation_period VARCHAR(20) NOT NULL,
                reviewer_code VARCHAR(20),
                performance_score DECIMAL(5,2),
                patient_care_score DECIMAL(5,2),
                teamwork_score DECIMAL(5,2),
                reliability_score DECIMAL(5,2),
                initiative_score DECIMAL(5,2),
                total_score DECIMAL(5,2),
                comments LONGTEXT,
                suggestions LONGTEXT,
                is_anonymous BOOLEAN DEFAULT TRUE,
                status ENUM('NOT_STARTED', 'IN_PROGRESS', 'SUBMITTED', 'INCOMPLETE') DEFAULT 'NOT_STARTED',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                submitted_at DATETIME,
                FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
                FOREIGN KEY (reviewer_employee_id) REFERENCES employees(id) ON DELETE CASCADE,
                INDEX idx_employee_id (employee_id),
                INDEX idx_reviewer_id (reviewer_employee_id),
                INDEX idx_period (evaluation_period),
                INDEX idx_status (status),
                UNIQUE KEY unique_peer_review (employee_id, reviewer_employee_id, evaluation_period)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS peer_reviews (
                id SERIAL PRIMARY KEY,
                employee_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
                reviewer_employee_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
                evaluation_period VARCHAR(20) NOT NULL,
                reviewer_code VARCHAR(20),
                performance_score DECIMAL(5,2),
                patient_care_score DECIMAL(5,2),
                teamwork_score DECIMAL(5,2),
                reliability_score DECIMAL(5,2),
                initiative_score DECIMAL(5,2),
                total_score DECIMAL(5,2),
                comments TEXT,
                suggestions TEXT,
                is_anonymous BOOLEAN DEFAULT TRUE,
                status VARCHAR(20) DEFAULT 'NOT_STARTED',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                submitted_at TIMESTAMP,
                UNIQUE(employee_id, reviewer_employee_id, evaluation_period)
            )";
        }

        $db->execute($sql);
        echo "✓ Peer reviews table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS peer_reviews");
        echo "✓ Peer reviews table dropped\n";
    }
}
