<?php
/**
 * Migration 001: Create Users Table
 */

namespace Database\Migrations;

use App\Config\Database;

class CreateUsersTable
{
    public static function up()
    {
        $db = Database::getInstance();
        $driver = DB_DRIVER;

        if ($driver === 'mysql') {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(120) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                role ENUM('ADMIN', 'HR', 'SUPERVISOR', 'EMPLOYEE', 'VIEWER') NOT NULL,
                department VARCHAR(100),
                is_active BOOLEAN DEFAULT TRUE,
                last_login DATETIME,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_role (role),
                INDEX idx_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(120) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                role VARCHAR(20) NOT NULL,
                department VARCHAR(100),
                is_active BOOLEAN DEFAULT TRUE,
                last_login TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        }

        $db->execute($sql);
        echo "✓ Users table created\n";
    }

    public static function down()
    {
        $db = Database::getInstance();
        $db->execute("DROP TABLE IF EXISTS users");
        echo "✓ Users table dropped\n";
    }
}
