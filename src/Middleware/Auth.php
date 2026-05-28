<?php
/**
 * Auth Middleware
 */

namespace App\Middleware;

class Auth
{
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Require login
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: /auth?action=login');
            exit;
        }
    }

    /**
     * Require role
     */
    public static function requireRole($role)
    {
        if (!self::isLoggedIn()) {
            header('Location: /auth?action=login');
            exit;
        }

        if ($_SESSION['user_role'] !== $role) {
            http_response_code(403);
            echo "Access denied";
            exit;
        }
    }

    /**
     * Get current user
     */
    public static function getCurrentUser()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user role
     */
    public static function getCurrentRole()
    {
        return $_SESSION['user_role'] ?? null;
    }
}
