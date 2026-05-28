<?php
/**
 * Application Run Script - Execute migrations
 */

require_once __DIR__ . '/src/Config/config.php';

use Database\Migrations as Migrations;

echo "\n=== Employee Performance Evaluation System - Database Setup ===\n\n";

$command = $argv[1] ?? 'migrate';

if ($command === 'migrate') {
    echo "Running migrations...\n\n";
    
    $migrations = [
        'Database\\Migrations\\CreateUsersTable',
        'Database\\Migrations\\CreateEmployeesTable',
        'Database\\Migrations\\CreateEvaluationsTable',
        'Database\\Migrations\\CreatePeerReviewsTable',
        'Database\\Migrations\\CreateEvaluationSummaryTable',
        'Database\\Migrations\\CreateAuditLogTable',
    ];

    foreach ($migrations as $migration) {
        if (class_exists($migration)) {
            echo "Running {$migration}...\n";
            $class = new $migration();
            $class->up();
        }
    }

    echo "\n✓ All migrations completed!\n\n";

} elseif ($command === 'rollback') {
    echo "Rolling back migrations...\n\n";
    
    $migrations = [
        'Database\\Migrations\\CreateAuditLogTable',
        'Database\\Migrations\\CreateEvaluationSummaryTable',
        'Database\\Migrations\\CreatePeerReviewsTable',
        'Database\\Migrations\\CreateEvaluationsTable',
        'Database\\Migrations\\CreateEmployeesTable',
        'Database\\Migrations\\CreateUsersTable',
    ];

    foreach ($migrations as $migration) {
        if (class_exists($migration)) {
            echo "Rolling back {$migration}...\n";
            $class = new $migration();
            $class->down();
        }
    }

    echo "\n✓ All migrations rolled back!\n\n";

} else {
    echo "Usage: php run-migrations.php [migrate|rollback]\n";
    echo "  migrate   - Run all migrations\n";
    echo "  rollback  - Rollback all migrations\n";
}
