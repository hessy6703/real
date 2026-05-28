<?php
/**
 * Evaluation Model
 */

namespace App\Models;

use App\Config\Database;

class Evaluation
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all evaluations
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT e.*, emp.full_name, emp.employee_id, emp.department 
                FROM evaluations e
                JOIN employees emp ON e.employee_id = emp.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['period'])) {
            $sql .= " AND e.evaluation_period = ?";
            $params[] = $filters['period'];
        }

        if (!empty($filters['department'])) {
            $sql .= " AND emp.department = ?";
            $params[] = $filters['department'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND e.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY e.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get evaluation by ID
     */
    public function getById($id)
    {
        $sql = "SELECT e.*, emp.full_name, emp.employee_id, sup.full_name as supervisor_name
                FROM evaluations e
                JOIN employees emp ON e.employee_id = emp.id
                LEFT JOIN employees sup ON e.supervisor_id = sup.id
                WHERE e.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Get evaluations by employee
     */
    public function getByEmployee($employeeId, $period = null)
    {
        $sql = "SELECT * FROM evaluations WHERE employee_id = ?";
        $params = [$employeeId];

        if ($period) {
            $sql .= " AND evaluation_period = ?";
            $params[] = $period;
        }

        $sql .= " ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Create evaluation
     */
    public function create($data)
    {
        $sql = "INSERT INTO evaluations (
                    employee_id, supervisor_id, evaluation_period,
                    performance_score, patient_care_score, teamwork_score,
                    reliability_score, initiative_score, supervisor_total,
                    supervisor_weighted, overall_comments, strengths,
                    areas_for_improvement, status, submitted_at, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $params = [
            $data['employee_id'],
            $data['supervisor_id'],
            $data['evaluation_period'],
            $data['performance_score'],
            $data['patient_care_score'],
            $data['teamwork_score'],
            $data['reliability_score'],
            $data['initiative_score'],
            $data['supervisor_total'],
            $data['supervisor_weighted'],
            $data['overall_comments'] ?? null,
            $data['strengths'] ?? null,
            $data['areas_for_improvement'] ?? null,
            $data['status'] ?? 'PENDING',
            date('Y-m-d H:i:s'),
        ];

        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Update evaluation
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

        $sql = "UPDATE evaluations SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, $params);
    }

    /**
     * Get peer reviews for employee
     */
    public function getPeerReviews($employeeId, $period = null)
    {
        $sql = "SELECT pr.*, emp.full_name as reviewer_name
                FROM peer_reviews pr
                JOIN employees emp ON pr.reviewer_employee_id = emp.id
                WHERE pr.employee_id = ? AND pr.status = 'SUBMITTED'";
        
        $params = [$employeeId];

        if ($period) {
            $sql .= " AND pr.evaluation_period = ?";
            $params[] = $period;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get evaluation history
     */
    public function getEvaluationHistory($employeeId, $limit = 10)
    {
        $sql = "SELECT * FROM evaluation_summary 
                WHERE employee_id = ?
                ORDER BY evaluation_period DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$employeeId, $limit]);
    }

    /**
     * Count flagged employees
     */
    public function countFlaggedEmployees()
    {
        $sql = "SELECT COUNT(*) as count FROM evaluation_summary WHERE final_score < 60";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
}
