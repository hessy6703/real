<?php
/**
 * Supervisor Evaluation Request Email Template
 */
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { background-color: white; padding: 20px; }
        .footer { background-color: #ecf0f1; padding: 10px; text-align: center; font-size: 12px; color: #7f8c8d; }
        .button { background-color: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 20px 0; }
        .highlight { background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Performance Evaluation Request</h1>
        </div>
        
        <div class="content">
            <p>Dear <?php echo htmlspecialchars($supervisorName); ?>,</p>
            
            <p>It's time to complete performance evaluations for your team members for the <?php echo htmlspecialchars($evaluationPeriod); ?> evaluation period.</p>
            
            <div class="highlight">
                <strong>Evaluation Details:</strong><br>
                Period: <?php echo htmlspecialchars($evaluationPeriod); ?><br>
                Employees to Evaluate: <?php echo $employeeCount; ?><br>
                Deadline: <?php echo htmlspecialchars($deadline); ?>
            </div>
            
            <p>Please complete all evaluations before the deadline to ensure timely processing.</p>
            
            <a href="<?php echo htmlspecialchars($formUrl); ?>" class="button">Complete Evaluations</a>
            
            <p><strong>What to do:</strong></p>
            <ol>
                <li>Click the button above to access the evaluation form</li>
                <li>Rate each employee across 5 competency categories</li>
                <li>Provide constructive comments and feedback</li>
                <li>Submit the evaluation</li>
            </ol>
            
            <p>If you have any questions, please contact the HR Department.</p>
            
            <p>Best regards,<br>
            HR Department</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Employee Performance Evaluation System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
