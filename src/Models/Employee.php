<?php
/**
 * Employee Model
 */

namespace App\Models;

use App\Config\Database;

class Employee
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all employees
     */
    public function getAll()
    {
        $sql = "SELECT * FROM employees ORDER BY full_name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get employee by ID
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM employees WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Get employees by department
     */
    public function getByDepartment($department)
    {
        $sql = "SELECT * FROM employees WHERE department = ? ORDER BY full_name";
        return $this->db->fetchAll($sql, [$department]);
    }

    /**
     * Create employee
     */
    public function create($data)
    {
        $sql = "INSERT INTO employees (employee_id, full_name, email, department, job_title, supervisor_id, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        return $this->db->execute($sql, [
            $data['employee_id'],
            $data['full_name'],
            $data['email'],
            $data['department'],
            $data['job_title'],
            $data['supervisor_id'] ?? null,
        ]);
    }

    /**
     * Update employee
     */
    public function update($id, $data)
    {
        $updates = [];
        $params = [];

        foreach ($data as $key => $value) {
            $updates[] = "{$key} = ?";
            $params[] = $value;
        }

        $params[] = $id;

        $sql = "UPDATE employees SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    /**
     * Delete employee
     */
    public function delete($id)
    {
        $sql = "DELETE FROM employees WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Get employees by supervisor
     */
    public function getBySupervisor($supervisorId)
    {
        $sql = "SELECT * FROM employees WHERE supervisor_id = ? ORDER BY full_name";
        return $this->db->fetchAll($sql, [$supervisorId]);
    }
}
