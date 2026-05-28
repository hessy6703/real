<?php
/**
 * Database Constants and Configuration
 */

define('DB_CONFIG', [
    'host' => DB_HOST,
    'user' => DB_USER,
    'password' => DB_PASS,
    'database' => DB_NAME,
    'driver' => DB_DRIVER,
    'port' => DB_PORT,
]);

define('EVALUATION_PERIODS', [
    'Q1' => ['start_month' => 1, 'end_month' => 3, 'label' => 'Q1'],
    'Q2' => ['start_month' => 4, 'end_month' => 6, 'label' => 'Q2'],
    'Q3' => ['start_month' => 7, 'end_month' => 9, 'label' => 'Q3'],
    'Q4' => ['start_month' => 10, 'end_month' => 12, 'label' => 'Q4'],
    'H1' => ['start_month' => 1, 'end_month' => 6, 'label' => 'H1'],
    'H2' => ['start_month' => 7, 'end_month' => 12, 'label' => 'H2'],
    'ANNUAL' => ['start_month' => 1, 'end_month' => 12, 'label' => 'Annual'],
]);

define('DEPARTMENTS', [
    'Clinical',
    'Administrative',
    'Finance',
    'IT',
    'HR',
    'Operations',
    'Marketing',
    'Sales',
]);

define('USER_ROLES', [
    'ADMIN' => 'Administrator',
    'HR' => 'HR Manager',
    'SUPERVISOR' => 'Supervisor',
    'EMPLOYEE' => 'Employee',
    'VIEWER' => 'Viewer',
]);

define('EVALUATION_STATUS', [
    'PENDING' => 'Pending',
    'IN_PROGRESS' => 'In Progress',
    'SUBMITTED' => 'Submitted',
    'REVIEWED' => 'Reviewed',
    'APPROVED' => 'Approved',
    'ARCHIVED' => 'Archived',
]);

define('PEER_REVIEW_STATUS', [
    'NOT_STARTED' => 'Not Started',
    'IN_PROGRESS' => 'In Progress',
    'SUBMITTED' => 'Submitted',
    'INCOMPLETE' => 'Incomplete',
]);

define('ERROR_MESSAGES', [
    'INVALID_SCORE' => 'Score must be between 0 and {max}',
    'MISSING_REQUIRED_FIELD' => 'Required field is missing: {field}',
    'EMPLOYEE_NOT_FOUND' => 'Employee not found: {id}',
    'INSUFFICIENT_PEER_REVIEWS' => 'Minimum {min} peer reviews required, {current} submitted',
    'SELF_REVIEW_NOT_ALLOWED' => 'Employees cannot review themselves',
    'DUPLICATE_REVIEW' => 'This peer has already submitted a review for this employee',
    'UNAUTHORIZED' => 'You do not have permission to access this resource',
    'INVALID_CREDENTIALS' => 'Invalid email or password',
    'DATABASE_ERROR' => 'Database error occurred',
]);

define('SUCCESS_MESSAGES', [
    'EVALUATION_SUBMITTED' => 'Evaluation submitted successfully',
    'PEER_REVIEW_SUBMITTED' => 'Peer review submitted successfully',
    'DATA_EXPORTED' => 'Data exported successfully',
    'REPORT_GENERATED' => 'Report generated successfully',
    'USER_CREATED' => 'User created successfully',
    'USER_UPDATED' => 'User updated successfully',
]);

define('PERMISSIONS', [
    'ADMIN' => [
        'create_evaluation_period',
        'view_all_evaluations',
        'edit_all_evaluations',
        'delete_evaluations',
        'manage_users',
        'configure_system',
        'export_data',
        'view_analytics',
    ],
    'HR' => [
        'create_evaluation_period',
        'view_all_evaluations',
        'manage_peer_assignments',
        'send_notifications',
        'view_analytics',
        'export_reports',
        'manage_flagged_employees',
    ],
    'SUPERVISOR' => [
        'submit_evaluation',
        'view_own_evaluations',
        'edit_own_evaluations',
        'view_team_evaluations',
    ],
    'EMPLOYEE' => [
        'view_own_evaluation',
        'submit_peer_review',
    ],
    'VIEWER' => [
        'view_aggregated_reports',
    ],
]);

define('DATA_RETENTION', [
    'normal' => 3,
    'terminated_employee' => 7,
    'dispute' => 10,
]);
