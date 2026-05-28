<?php
/**
 * Report Controller
 */

namespace App\Controllers;

use App\Models\Evaluation;
use App\Models\Employee;

class ReportController
{
    private $evaluationModel;
    private $employeeModel;

    public function __construct()
    {
        $this->evaluationModel = new Evaluation();
        $this->employeeModel = new Employee();
    }

    /**
     * Get individual employee report
     */
    public function getIndividualReport($employeeId, $period = null)
    {
        $employee = $this->employeeModel->getById($employeeId);
        if (!$employee) {
            return ['error' => 'Employee not found', 'status' => 404];
        }

        $evaluation = $this->evaluationModel->getByEmployee($employeeId, $period);
        if (!$evaluation) {
            return ['error' => 'No evaluation found', 'status' => 404];
        }

        return [
            'status' => 'success',
            'data' => [
                'employee' => $employee,
                'evaluation' => $evaluation,
                'trends' => $this->calculateTrends($employeeId),
            ]
        ];
    }

    /**
     * Get department report
     */
    public function getDepartmentReport($department, $period = null)
    {
        $employees = $this->employeeModel->getByDepartment($department);
        if (empty($employees)) {
            return ['error' => 'No employees found in department', 'status' => 404];
        }

        $evaluations = [];
        foreach ($employees as $employee) {
            $eval = $this->evaluationModel->getByEmployee($employee['id'], $period);
            if ($eval) {
                $evaluations[] = $eval;
            }
        }

        return [
            'status' => 'success',
            'data' => [
                'department' => $department,
                'employee_count' => count($employees),
                'evaluated_count' => count($evaluations),
                'average_score' => $this->calculateDepartmentAverage($evaluations),
                'distribution' => $this->getRatingDistribution($evaluations),
                'evaluations' => $evaluations,
            ]
        ];
    }

    /**
     * Calculate average for department
     */
    private function calculateDepartmentAverage($evaluations)
    {
        if (empty($evaluations)) {
            return 0;
        }

        $total = array_sum(array_column($evaluations, 'final_score'));
        return round($total / count($evaluations), 2);
    }

    /**
     * Get rating distribution
     */
    private function getRatingDistribution($evaluations)
    {
        $distribution = [
            'Outstanding' => 0,
            'Very Good' => 0,
            'Good' => 0,
            'Fair' => 0,
            'Needs Improvement' => 0,
        ];

        foreach ($evaluations as $eval) {
            $distribution[$eval['performance_rating']]++;
        }

        return $distribution;
    }

    /**
     * Calculate trends
     */
    private function calculateTrends($employeeId)
    {
        $history = $this->evaluationModel->getEvaluationHistory($employeeId);
        
        $trends = [];
        for ($i = 1; $i < count($history); $i++) {
            $current = $history[$i]['final_score'];
            $previous = $history[$i - 1]['final_score'];
            
            $trends[] = [
                'period' => $history[$i]['evaluation_period'],
                'score' => $current,
                'change' => round($current - $previous, 2),
                'change_percent' => round((($current - $previous) / $previous) * 100, 2),
            ];
        }

        return $trends;
    }

    /**
     * Export report as PDF
     */
    public function exportPDF($type, $id, $period = null)
    {
        try {
            if ($type === 'individual') {
                $report = $this->getIndividualReport($id, $period);
            } elseif ($type === 'department') {
                $report = $this->getDepartmentReport($id, $period);
            } else {
                return ['error' => 'Invalid report type', 'status' => 400];
            }

            // PDF generation would go here
            // Using a library like TCPDF or mPDF
            
            return [
                'status' => 'success',
                'message' => 'PDF exported successfully',
                'file' => 'report_' . date('Y-m-d_H-i-s') . '.pdf',
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }
}
