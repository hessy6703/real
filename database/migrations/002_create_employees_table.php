<?php
/**
 * Migration 002: Create Employees Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreateEmployeesTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                employee_id VARCHAR(50) UNIQUE NOT NULL,
                full_name VARCHAR(200) NOT NULL,
                email VARCHAR(120) NOT NULL,
                department VARCHAR(100) NOT NULL,
                job_title VARCHAR(150) NOT NULL,
                supervisor_id INT,
                manager_id INT,
                user_id INT,
                is_active BOOLEAN DEFAULT TRUE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (supervisor_id) REFERENCES employees(id) ON DELETE SET NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_employee_id (employee_id),
                INDEX idx_department (department),
                INDEX idx_supervisor_id (supervisor_id),
                INDEX idx_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS employees (
                id SERIAL PRIMARY KEY,
                employee_id VARCHAR(50) UNIQUE NOT NULL,
                full_name VARCHAR(200) NOT NULL,
                email VARCHAR(120) NOT NULL,
                department VARCHAR(100) NOT NULL,
                job_title VARCHAR(150) NOT NULL,
                supervisor_id INT REFERENCES employees(id),
                manager_id INT,
                user_id INT REFERENCES users(id),
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        }

        $db->execute($sql);
        echo "✓ Employees table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS employees");
        echo "✓ Employees table dropped\n";
    }
}
