<?php
/**
 * Validation Service
 * 
 * Validates all input data
 */

namespace App\Services;

class ValidationService
{
    /**
     * Validate supervisor evaluation scores
     */
    public static function validateSupervisorScores($scores)
    {
        $errors = [];

        foreach (SCORING_CATEGORIES as $category => $config) {
            if (!isset($scores[$category])) {
                $errors[$category] = "Score for {$config['label']} is required";
                continue;
            }

            $score = $scores[$category];

            // Check if numeric
            if (!is_numeric($score)) {
                $errors[$category] = "{$config['label']} must be a number";
                continue;
            }

            // Check range
            if ($score < 0 || $score > $config['max']) {
                $errors[$category] = "{$config['label']} must be between 0 and {$config['max']}";
            }
        }

        return $errors;
    }

    /**
     * Validate peer review scores
     */
    public static function validatePeerScores($scores)
    {
        return self::validateSupervisorScores($scores);
    }

    /**
     * Validate email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate password
     */
    public static function validatePassword($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain lowercase letters';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain uppercase letters';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain numbers';
        }

        return $errors;
    }

    /**
     * Validate username
     */
    public static function validateUsername($username)
    {
        if (strlen($username) < 3) {
            return ['Username must be at least 3 characters'];
        }

        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
            return ['Username can only contain letters, numbers, underscores, and hyphens'];
        }

        return [];
    }

    /**
     * Validate employee ID
     */
    public static function validateEmployeeId($id)
    {
        if (empty($id)) {
            return ['Employee ID is required'];
        }

        // Adjust pattern based on your ID format
        if (!preg_match('/^[A-Z0-9-]+$/', $id)) {
            return ['Invalid Employee ID format'];
        }

        return [];
    }

    /**
     * Validate evaluation period
     */
    public static function validateEvaluationPeriod($period)
    {
        if (!isset(EVALUATION_PERIODS[$period])) {
            return ['Invalid evaluation period: ' . $period];
        }
        return [];
    }

    /**
     * Validate no self-review
     */
    public static function validateNoSelfReview($reviewerEmployeeId, $revieweeEmployeeId)
    {
        if ($reviewerEmployeeId === $revieweeEmployeeId) {
            return ['Employees cannot review themselves'];
        }
        return [];
    }

    /**
     * Sanitize input
     */
    public static function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate required fields
     */
    public static function validateRequired($data, $requiredFields)
    {
        $errors = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }

        return $errors;
    }
}
