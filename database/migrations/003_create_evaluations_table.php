<?php
/**
 * Migration 003: Create Evaluations Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreateEvaluationsTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS evaluations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                employee_id INT NOT NULL,
                supervisor_id INT NOT NULL,
                evaluation_period VARCHAR(20) NOT NULL,
                performance_score DECIMAL(5,2),
                patient_care_score DECIMAL(5,2),
                teamwork_score DECIMAL(5,2),
                reliability_score DECIMAL(5,2),
                initiative_score DECIMAL(5,2),
                supervisor_total DECIMAL(5,2),
                supervisor_weighted DECIMAL(5,2),
                overall_comments LONGTEXT,
                strengths LONGTEXT,
                areas_for_improvement LONGTEXT,
                status ENUM('PENDING', 'IN_PROGRESS', 'SUBMITTED', 'REVIEWED', 'APPROVED', 'ARCHIVED') DEFAULT 'PENDING',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                submitted_at DATETIME,
                FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
                FOREIGN KEY (supervisor_id) REFERENCES employees(id) ON DELETE CASCADE,
                INDEX idx_employee_id (employee_id),
                INDEX idx_supervisor_id (supervisor_id),
                INDEX idx_period (evaluation_period),
                INDEX idx_status (status),
                UNIQUE KEY unique_evaluation (employee_id, supervisor_id, evaluation_period)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS evaluations (
                id SERIAL PRIMARY KEY,
                employee_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
                supervisor_id INT NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
                evaluation_period VARCHAR(20) NOT NULL,
                performance_score DECIMAL(5,2),
                patient_care_score DECIMAL(5,2),
                teamwork_score DECIMAL(5,2),
                reliability_score DECIMAL(5,2),
                initiative_score DECIMAL(5,2),
                supervisor_total DECIMAL(5,2),
                supervisor_weighted DECIMAL(5,2),
                overall_comments TEXT,
                strengths TEXT,
                areas_for_improvement TEXT,
                status VARCHAR(20) DEFAULT 'PENDING',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                submitted_at TIMESTAMP,
                UNIQUE(employee_id, supervisor_id, evaluation_period)
            )";
        }

        $db->execute($sql);
        echo "✓ Evaluations table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS evaluations");
        echo "✓ Evaluations table dropped\n";
    }
}
