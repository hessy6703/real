<?php
/**
 * Calculation Service
 * 
 * Handles all performance score calculations
 */

namespace App\Services;

class CalculationService
{
    /**
     * Calculate final evaluation score
     * 
     * @param array $supervisorScores - Individual supervisor category scores
     * @param array $peerScores - Array of peer review total scores
     * @return array - Contains total, weighted, and final scores
     */
    public static function calculateFinalScore($supervisorScores, $peerScores)
    {
        // Validate inputs
        if (empty($supervisorScores) || !is_array($supervisorScores)) {
            throw new \InvalidArgumentException('Invalid supervisor scores');
        }

        if (count($peerScores) < MIN_PEER_REVIEWS) {
            throw new \InvalidArgumentException(
                "Minimum {" . MIN_PEER_REVIEWS . "} peer reviews required, " . count($peerScores) . " submitted"
            );
        }

        // Calculate supervisor total
        $supervisorTotal = self::calculateSupervisorTotal($supervisorScores);
        $supervisorWeighted = self::applyWeight($supervisorTotal, SUPERVISOR_WEIGHT);

        // Calculate peer average
        $peerAverage = self::calculatePeerAverage($peerScores);
        $peerWeighted = self::applyWeight($peerAverage, PEER_WEIGHT);

        // Calculate final score
        $finalScore = $supervisorWeighted + $peerWeighted;

        // Get performance rating
        $rating = self::getPerformanceRating($finalScore);

        return [
            'supervisor_total' => round($supervisorTotal, 2),
            'supervisor_weighted' => round($supervisorWeighted, 2),
            'peer_average' => round($peerAverage, 2),
            'peer_weighted' => round($peerWeighted, 2),
            'final_score' => round($finalScore, 2),
            'rating' => $rating,
            'status' => self::getStatus($finalScore),
        ];
    }

    /**
     * Calculate supervisor total score
     */
    private static function calculateSupervisorTotal($scores)
    {
        $total = 0;
        foreach (SCORING_CATEGORIES as $category => $config) {
            $score = $scores[$category] ?? 0;
            // Validate score is within max
            if ($score > $config['max'] || $score < 0) {
                throw new \InvalidArgumentException(
                    "Score for {$category} must be between 0 and " . $config['max']
                );
            }
            $total += $score;
        }
        return $total;
    }

    /**
     * Calculate peer average score
     */
    private static function calculatePeerAverage($peerScores)
    {
        if (empty($peerScores)) {
            return 0;
        }
        return array_sum($peerScores) / count($peerScores);
    }

    /**
     * Apply weighting to a score
     */
    private static function applyWeight($score, $weight)
    {
        return $score * $weight;
    }

    /**
     * Get performance rating based on score
     */
    public static function getPerformanceRating($score)
    {
        if ($score >= RATING_OUTSTANDING) {
            return 'Outstanding';
        } elseif ($score >= RATING_VERY_GOOD) {
            return 'Very Good';
        } elseif ($score >= RATING_GOOD) {
            return 'Good';
        } elseif ($score >= RATING_FAIR) {
            return 'Fair';
        } else {
            return 'Needs Improvement';
        }
    }

    /**
     * Get evaluation status
     */
    public static function getStatus($score)
    {
        if ($score < 60) {
            return 'FLAG: Needs Improvement';
        } elseif ($score < 70) {
            return 'Attention: Fair Rating';
        } else {
            return 'Reviewed';
        }
    }

    /**
     * Calculate department average
     */
    public static function calculateDepartmentAverage($scores)
    {
        if (empty($scores)) {
            return 0;
        }
        return array_sum($scores) / count($scores);
    }

    /**
     * Calculate performance trends
     */
    public static function calculateTrends($currentScore, $previousScore)
    {
        if ($previousScore === null) {
            return null;
        }

        $difference = $currentScore - $previousScore;
        $percentChange = ($difference / $previousScore) * 100;

        return [
            'previous' => $previousScore,
            'current' => $currentScore,
            'difference' => round($difference, 2),
            'percent_change' => round($percentChange, 2),
            'trend' => $difference > 0 ? 'UP' : ($difference < 0 ? 'DOWN' : 'STABLE'),
        ];
    }
}
