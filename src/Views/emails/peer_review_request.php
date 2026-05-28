<?php
/**
 * Peer Review Request Email Template (Anonymous)
 */
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background-color: #27ae60; color: white; padding: 20px; text-align: center; }
        .content { background-color: white; padding: 20px; }
        .footer { background-color: #ecf0f1; padding: 10px; text-align: center; font-size: 12px; color: #7f8c8d; }
        .button { background-color: #27ae60; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 20px 0; }
        .highlight { background-color: #d5f4e6; padding: 15px; border-left: 4px solid #27ae60; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Request for Anonymous Feedback</h1>
        </div>
        
        <div class="content">
            <p>Dear Colleague,</p>
            
            <p>Your feedback is valuable! We're asking you to provide honest, constructive feedback about <?php echo htmlspecialchars($employeeName); ?>'s performance.</p>
            
            <div class="highlight">
                <strong>🔐 Your response will be completely anonymous and confidential.</strong>
            </div>
            
            <p>Your identity will remain confidential. Please take about 10 minutes to complete this brief evaluation form.</p>
            
            <a href="<?php echo htmlspecialchars($formUrl); ?>" class="button">Provide Feedback</a>
            
            <p><strong>What we're asking:</strong></p>
            <ul>
                <li>Rate performance across 5 key competency areas</li>
                <li>Provide optional comments about strengths</li>
                <li>Suggest areas for improvement</li>
            </ul>
            
            <p><strong>Important:</strong> This is an anonymous survey. Your name will not be recorded or shared.</p>
            
            <p><strong>Deadline:</strong> <?php echo htmlspecialchars($deadline); ?></p>
            
            <p>Thank you for your honest feedback!</p>
            
            <p>Best regards,<br>
            HR Department</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Employee Performance Evaluation System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
