<?php
/**
 * Evaluation Controller
 */

namespace App\Controllers;

use App\Models\Evaluation;
use App\Models\Employee;
use App\Services\CalculationService;
use App\Services\ValidationService;
use App\Services\NotificationService;

class EvaluationController
{
    private $evaluationModel;
    private $employeeModel;
    private $notificationService;

    public function __construct()
    {
        $this->evaluationModel = new Evaluation();
        $this->employeeModel = new Employee();
        $this->notificationService = new NotificationService();
    }

    /**
     * Get evaluation form page
     */
    public function showForm($employeeId)
    {
        $employee = $this->employeeModel->getById($employeeId);
        if (!$employee) {
            return ['error' => 'Employee not found', 'status' => 404];
        }

        return [
            'status' => 'success',
            'data' => [
                'employee' => $employee,
                'scoring_categories' => SCORING_CATEGORIES,
            ]
        ];
    }

    /**
     * Submit evaluation
     */
    public function submit($data)
    {
        // Validate inputs
        $errors = ValidationService::validateSupervisorScores($data['scores']);
        if (!empty($errors)) {
            return ['error' => 'Validation failed', 'details' => $errors, 'status' => 422];
        }

        try {
            // Calculate scores
            $peerScores = $this->getPeerScores($data['employee_id']);
            $calculations = CalculationService::calculateFinalScore($data['scores'], $peerScores);

            // Save evaluation
            $evaluationId = $this->evaluationModel->create([
                'employee_id' => $data['employee_id'],
                'supervisor_id' => $_SESSION['user_id'],
                'evaluation_period' => $data['evaluation_period'],
                'performance_score' => $data['scores']['performance'],
                'patient_care_score' => $data['scores']['patient_care'],
                'teamwork_score' => $data['scores']['teamwork'],
                'reliability_score' => $data['scores']['reliability'],
                'initiative_score' => $data['scores']['initiative'],
                'supervisor_total' => $calculations['supervisor_total'],
                'supervisor_weighted' => $calculations['supervisor_weighted'],
                'overall_comments' => $data['overall_comments'] ?? null,
                'strengths' => $data['strengths'] ?? null,
                'areas_for_improvement' => $data['areas_for_improvement'] ?? null,
                'status' => 'SUBMITTED',
            ]);

            // Send notifications
            $this->notificationService->sendCompletionNotification(
                $_SESSION['user_email'],
                $employee['full_name'],
                $_SESSION['user_name']
            );

            return [
                'status' => 'success',
                'message' => 'Evaluation submitted successfully',
                'evaluation_id' => $evaluationId,
                'calculations' => $calculations,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Get evaluation by ID
     */
    public function getById($evaluationId)
    {
        $evaluation = $this->evaluationModel->getById($evaluationId);
        if (!$evaluation) {
            return ['error' => 'Evaluation not found', 'status' => 404];
        }

        return [
            'status' => 'success',
            'data' => $evaluation,
        ];
    }

    /**
     * Get peer scores for employee
     */
    private function getPeerScores($employeeId)
    {
        // Get peer reviews from database
        $peerReviews = $this->evaluationModel->getPeerReviews($employeeId);
        
        $scores = [];
        foreach ($peerReviews as $review) {
            $scores[] = $review['total_score'];
        }

        return $scores;
    }
}
