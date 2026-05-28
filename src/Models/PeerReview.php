<?php
/**
 * Peer Review Model
 */

namespace App\Models;

use App\Config\Database;

class PeerReview
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all peer reviews
     */
    public function getAll($filters = [])
    {
        $sql = "SELECT pr.*, emp.full_name, emp.employee_id
                FROM peer_reviews pr
                JOIN employees emp ON pr.employee_id = emp.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['period'])) {
            $sql .= " AND pr.evaluation_period = ?";
            $params[] = $filters['period'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND pr.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY pr.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get peer reviews by employee
     */
    public function getByEmployee($employeeId, $period = null)
    {
        $sql = "SELECT pr.*, emp.full_name as reviewer_name
                FROM peer_reviews pr
                LEFT JOIN employees emp ON pr.reviewer_employee_id = emp.id
                WHERE pr.employee_id = ? AND pr.status = 'SUBMITTED'";
        
        $params = [$employeeId];

        if ($period) {
            $sql .= " AND pr.evaluation_period = ?";
            $params[] = $period;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Create peer review
     */
    public function create($data)
    {
        $sql = "INSERT INTO peer_reviews (
                    employee_id, reviewer_employee_id, evaluation_period,
                    performance_score, patient_care_score, teamwork_score,
                    reliability_score, initiative_score, total_score,
                    comments, suggestions, is_anonymous, status, submitted_at, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $params = [
            $data['employee_id'],
            $data['reviewer_employee_id'],
            $data['evaluation_period'],
            $data['performance_score'],
            $data['patient_care_score'],
            $data['teamwork_score'],
            $data['reliability_score'],
            $data['initiative_score'],
            $data['total_score'],
            $data['comments'] ?? null,
            $data['suggestions'] ?? null,
            $data['is_anonymous'] ?? 1,
            $data['status'] ?? 'SUBMITTED',
        ];

        $this->db->execute($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Count peer reviews for employee in period
     */
    public function countForEmployee($employeeId, $period)
    {
        $sql = "SELECT COUNT(*) as count FROM peer_reviews 
                WHERE employee_id = ? AND evaluation_period = ? AND status = 'SUBMITTED'";
        
        $result = $this->db->fetch($sql, [$employeeId, $period]);
        return $result['count'] ?? 0;
    }

    /**
     * Get employee list for peer reviews
     */
    public function getEmployeeList()
    {
        $sql = "SELECT id, full_name, employee_id, department FROM employees WHERE is_active = 1 ORDER BY full_name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Check if duplicate review exists
     */
    public function isDuplicate($employeeId, $reviewerId, $period)
    {
        $sql = "SELECT id FROM peer_reviews 
                WHERE employee_id = ? AND reviewer_employee_id = ? AND evaluation_period = ?";
        
        $result = $this->db->fetch($sql, [$employeeId, $reviewerId, $period]);
        return !empty($result);
    }
}
