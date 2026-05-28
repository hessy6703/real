<?php
/**
 * Application Configuration
 * 
 * Load environment variables and set up application constants
 */

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'performance_db');
define('DB_DRIVER', $_ENV['DB_DRIVER'] ?? 'mysql');
define('DB_PORT', $_ENV['DB_PORT'] ?? 3306);

// Google Sheets API Configuration
define('GOOGLE_PROJECT_ID', $_ENV['GOOGLE_PROJECT_ID'] ?? '');
define('GOOGLE_CREDENTIALS_FILE', $_ENV['GOOGLE_CREDENTIALS_FILE'] ?? '');
define('GOOGLE_SHEETS_ID_SUPERVISOR', $_ENV['GOOGLE_SHEETS_ID_SUPERVISOR'] ?? '');
define('GOOGLE_SHEETS_ID_PEER', $_ENV['GOOGLE_SHEETS_ID_PEER'] ?? '');
define('GOOGLE_SHEETS_ID_SUMMARY', $_ENV['GOOGLE_SHEETS_ID_SUMMARY'] ?? '');

// Application Configuration
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Employee Performance Evaluation System');
define('APP_VERSION', $_ENV['APP_VERSION'] ?? '1.0.0');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? false);
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');
define('APP_TIMEZONE', $_ENV['APP_TIMEZONE'] ?? 'UTC');
define('SECRET_KEY', $_ENV['SECRET_KEY'] ?? 'change-me-in-production');

// Email Configuration
define('MAIL_FROM', $_ENV['MAIL_FROM'] ?? 'noreply@yourcompany.com');
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME'] ?? 'Performance Evaluation System');
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 2525);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME'] ?? '');
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD'] ?? '');
define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION'] ?? 'tls');

// Session Configuration
define('SESSION_TIMEOUT', $_ENV['SESSION_TIMEOUT'] ?? 3600);
define('REMEMBER_ME_DURATION', $_ENV['REMEMBER_ME_DURATION'] ?? 604800); // 7 days

// Logging Configuration
define('LOG_LEVEL', $_ENV['LOG_LEVEL'] ?? 'info');
define('LOG_PATH', dirname(__DIR__) . '/storage/logs');
define('LOG_FILE', 'application.log');

// Scoring Configuration
define('SCORING_CATEGORIES', [
    'performance' => ['max' => 30, 'label' => 'Performance'],
    'patient_care' => ['max' => 20, 'label' => 'Patient Care'],
    'teamwork' => ['max' => 20, 'label' => 'Teamwork'],
    'reliability' => ['max' => 15, 'label' => 'Reliability'],
    'initiative' => ['max' => 15, 'label' => 'Initiative & Compliance'],
]);
define('TOTAL_SCORE_MAX', 100);
define('SUPERVISOR_WEIGHT', 0.4);
define('PEER_WEIGHT', 0.6);
define('MIN_PEER_REVIEWS', 4);

// Performance Rating Thresholds
define('RATING_OUTSTANDING', 90);
define('RATING_VERY_GOOD', 80);
define('RATING_GOOD', 70);
define('RATING_FAIR', 60);

// Paths
define('BASE_PATH', dirname(__DIR__));
define('SRC_PATH', BASE_PATH . '/src');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('VIEWS_PATH', SRC_PATH . '/Views');
define('UPLOADS_PATH', STORAGE_PATH . '/uploads');

// File Upload Configuration
define('MAX_UPLOAD_SIZE', 10485760); // 10MB
define('ALLOWED_UPLOAD_TYPES', ['pdf', 'doc', 'docx', 'xlsx', 'xls']);

// Pagination
define('ITEMS_PER_PAGE', 25);

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

return true;
