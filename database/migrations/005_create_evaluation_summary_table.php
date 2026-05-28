<?php
/**
 * Migration 005: Create Evaluation Summary Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreateEvaluationSummaryTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS evaluation_summary (
                id INT AUTO_INCREMENT PRIMARY KEY,
                employee_id INT NOT NULL UNIQUE,
                evaluation_period VARCHAR(20) NOT NULL,
                supervisor_total DECIMAL(5,2),
                supervisor_weighted DECIMAL(5,2),
                peer_average DECIMAL(5,2),
                peer_count INT DEFAULT 0,
                peer_weighted DECIMAL(5,2),
                final_score DECIMAL(5,2),
                performance_rating VARCHAR(50),
                status VARCHAR(50),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
                INDEX idx_employee_id (employee_id),
                INDEX idx_period (evaluation_period),
                INDEX idx_rating (performance_rating),
                UNIQUE KEY unique_summary (employee_id, evaluation_period)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS evaluation_summary (
                id SERIAL PRIMARY KEY,
                employee_id INT NOT NULL UNIQUE REFERENCES employees(id) ON DELETE CASCADE,
                evaluation_period VARCHAR(20) NOT NULL,
                supervisor_total DECIMAL(5,2),
                supervisor_weighted DECIMAL(5,2),
                peer_average DECIMAL(5,2),
                peer_count INT DEFAULT 0,
                peer_weighted DECIMAL(5,2),
                final_score DECIMAL(5,2),
                performance_rating VARCHAR(50),
                status VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(employee_id, evaluation_period)
            )";
        }

        $db->execute($sql);
        echo "✓ Evaluation summary table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS evaluation_summary");
        echo "✓ Evaluation summary table dropped\n";
    }
}
