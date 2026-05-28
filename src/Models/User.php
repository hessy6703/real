<?php
/**
 * User Model
 */

namespace App\Models;

use App\Config\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all users
     */
    public function getAll()
    {
        $sql = "SELECT id, username, email, first_name, last_name, role, department, is_active, created_at 
                FROM users ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get user by ID
     */
    public function getById($id)
    {
        $sql = "SELECT id, username, email, first_name, last_name, role, department, is_active, last_login, created_at 
                FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Get user by email
     */
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }

    /**
     * Create user
     */
    public function create($data)
    {
        $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, role, department, is_active, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())";
        
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $params = [
            $data['username'],
            $data['email'],
            $passwordHash,
            $data['first_name'],
            $data['last_name'],
            $data['role'] ?? 'EMPLOYEE',
            $data['department'] ?? null,
        ];

        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Verify password
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->getByEmail($email);
        
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password_hash']);
    }

    /**
     * Update user
     */
    public function update($id, $data)
    {
        $updates = [];
        $params = [];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $updates[] = "password_hash = ?";
                $params[] = password_hash($value, PASSWORD_BCRYPT);
            } else {
                $updates[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        $params[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    /**
     * Update last login
     */
    public function updateLastLogin($id)
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        $sql = "SELECT * FROM users WHERE role = ? AND is_active = 1 ORDER BY first_name, last_name";
        return $this->db->fetchAll($sql, [$role]);
    }
}
