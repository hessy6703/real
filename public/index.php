<?php
/**
 * Entry point for the application
 */

require_once '../src/Config/config.php';

use App\Middleware\Auth;

// Simple router
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = str_replace('/real', '', $requestUri); // Adjust based on your setup

// Route the request
switch (true) {
    case $requestUri === '/':
        handleHomeRequest();
        break;
    case strpos($requestUri, '/dashboard') === 0:
        handleDashboardRequest();
        break;
    case strpos($requestUri, '/evaluation') === 0:
        handleEvaluationRequest();
        break;
    case strpos($requestUri, '/peer-review') === 0:
        handlePeerReviewRequest();
        break;
    case strpos($requestUri, '/reports') === 0:
        handleReportRequest();
        break;
    case strpos($requestUri, '/auth') === 0:
        handleAuthRequest();
        break;
    case strpos($requestUri, '/api') === 0:
        handleApiRequest();
        break;
    default:
        http_response_code(404);
        echo "Page not found";
        break;
}

function handleHomeRequest()
{
    include VIEWS_PATH . '/home.php';
}

function handleDashboardRequest()
{
    Auth::requireLogin();
    include VIEWS_PATH . '/dashboard/index.php';
}

function handleEvaluationRequest()
{
    Auth::requireLogin();
    Auth::requireRole('SUPERVISOR');
    include VIEWS_PATH . '/evaluation/form.php';
}

function handlePeerReviewRequest()
{
    // No auth required for peer reviews (anonymous)
    include VIEWS_PATH . '/peer-review/form.php';
}

function handleReportRequest()
{
    Auth::requireLogin();
    Auth::requireRole('HR');
    include VIEWS_PATH . '/reports/index.php';
}

function handleAuthRequest()
{
    $action = $_GET['action'] ?? 'login';
    
    if ($action === 'logout') {
        session_destroy();
        header('Location: /');
        exit;
    }
    
    include VIEWS_PATH . '/auth/login.php';
}

function handleApiRequest()
{
    header('Content-Type: application/json');
    // API routing logic
    // See API documentation for endpoints
}
