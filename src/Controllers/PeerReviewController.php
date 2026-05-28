<?php
/**
 * Peer Review Controller
 */

namespace App\Controllers;

use App\Models\PeerReview;
use App\Services\ValidationService;
use App\Services\CalculationService;

class PeerReviewController
{
    private $peerReviewModel;

    public function __construct()
    {
        $this->peerReviewModel = new PeerReview();
    }

    /**
     * Get anonymous peer review form
     */
    public function showForm()
    {
        return [
            'status' => 'success',
            'data' => [
                'scoring_categories' => SCORING_CATEGORIES,
                'employees' => $this->getEmployeeList(),
            ]
        ];
    }

    /**
     * Submit peer review
     */
    public function submit($data)
    {
        // Validate no self-review
        $errors = ValidationService::validateNoSelfReview(
            $data['reviewer_employee_id'],
            $data['employee_id']
        );
        if (!empty($errors)) {
            return ['error' => 'Validation failed', 'details' => $errors, 'status' => 422];
        }

        // Validate scores
        $errors = ValidationService::validatePeerScores($data['scores']);
        if (!empty($errors)) {
            return ['error' => 'Validation failed', 'details' => $errors, 'status' => 422];
        }

        try {
            // Calculate total
            $total = array_sum($data['scores']);

            // Save peer review
            $reviewId = $this->peerReviewModel->create([
                'employee_id' => $data['employee_id'],
                'reviewer_employee_id' => $data['reviewer_employee_id'],
                'evaluation_period' => $data['evaluation_period'],
                'performance_score' => $data['scores']['performance'],
                'patient_care_score' => $data['scores']['patient_care'],
                'teamwork_score' => $data['scores']['teamwork'],
                'reliability_score' => $data['scores']['reliability'],
                'initiative_score' => $data['scores']['initiative'],
                'total_score' => $total,
                'comments' => $data['comments'] ?? null,
                'suggestions' => $data['suggestions'] ?? null,
                'is_anonymous' => true,
                'status' => 'SUBMITTED',
            ]);

            return [
                'status' => 'success',
                'message' => 'Peer review submitted successfully',
                'review_id' => $reviewId,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Get employee list (for dropdown)
     */
    private function getEmployeeList()
    {
        // Return list of employees to review
        return $this->peerReviewModel->getEmployeeList();
    }

    /**
     * Get peer reviews for employee
     */
    public function getReviewsForEmployee($employeeId)
    {
        $reviews = $this->peerReviewModel->getByEmployee($employeeId);
        
        return [
            'status' => 'success',
            'data' => $reviews,
            'count' => count($reviews),
            'average_score' => $this->calculateAverage($reviews),
        ];
    }

    /**
     * Calculate average score
     */
    private function calculateAverage($reviews)
    {
        if (empty($reviews)) {
            return 0;
        }

        $total = array_sum(array_column($reviews, 'total_score'));
        return round($total / count($reviews), 2);
    }
}
