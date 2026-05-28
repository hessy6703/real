<?php
/**
 * Database Connection Class
 * 
 * Handles database connections and queries
 */

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    private $driver;

    private function __construct()
    {
        try {
            $this->driver = DB_DRIVER;
            $host = DB_HOST;
            $dbname = DB_NAME;
            $user = DB_USER;
            $pass = DB_PASS;
            $port = DB_PORT;

            if ($this->driver === 'mysql') {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            } elseif ($this->driver === 'pgsql') {
                $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            } else {
                throw new PDOException("Unsupported database driver: {$this->driver}");
            }

            $this->connection = new PDO(
                $dsn,
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            $this->logError('Database Connection Error: ' . $e->getMessage());
            throw new PDOException('Failed to connect to database');
        }
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Execute a prepared statement
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError('Query Error: ' . $e->getMessage());
            throw new PDOException('Database query failed');
        }
    }

    /**
     * Get all rows
     */
    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Get single row
     */
    public function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Execute insert/update/delete
     */
    public function execute($sql, $params = [])
    {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * Log errors
     */
    private function logError($message)
    {
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0755, true);
        }
        error_log($message . PHP_EOL, 3, LOG_PATH . '/database.log');
    }

    /**
     * Prevent cloning
     */
    private function __clone()
    {
    }

    /**
     * Prevent serialization
     */
    private function __sleep()
    {
    }
}
