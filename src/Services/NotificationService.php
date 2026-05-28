<?php
/**
 * Notification Service
 * 
 * Handles all email notifications
 */

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificationService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configureMail();
    }

    /**
     * Configure mail settings
     */
    private function configureMail()
    {
        try {
            // Use SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = MAIL_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = MAIL_USERNAME;
            $this->mailer->Password = MAIL_PASSWORD;
            $this->mailer->SMTPSecure = MAIL_ENCRYPTION;
            $this->mailer->Port = MAIL_PORT;
            $this->mailer->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            throw new \RuntimeException('Mail configuration error: ' . $e->getMessage());
        }
    }

    /**
     * Send supervisor evaluation request
     */
    public function sendSupervisorRequest($supervisorEmail, $supervisorName, $evaluationPeriod, $employeeCount)
    {
        $subject = "Action Required: Complete Performance Evaluations - {$evaluationPeriod}";
        
        $body = $this->getTemplate('supervisor_request', [
            'supervisorName' => $supervisorName,
            'evaluationPeriod' => $evaluationPeriod,
            'employeeCount' => $employeeCount,
            'formUrl' => APP_URL . '/evaluation/form',
            'deadline' => date('F d, Y', strtotime('+2 weeks')),
        ]);

        return $this->send($supervisorEmail, $subject, $body);
    }

    /**
     * Send peer review request (anonymous)
     */
    public function sendPeerReviewRequest($peerEmail, $employeeName, $anonymousLink)
    {
        $subject = "Request: Anonymous Performance Feedback";
        
        $body = $this->getTemplate('peer_review_request', [
            'employeeName' => $employeeName,
            'formUrl' => $anonymousLink,
            'deadline' => date('F d, Y', strtotime('+3 weeks')),
        ]);

        return $this->send($peerEmail, $subject, $body);
    }

    /**
     * Send completion notification to HR
     */
    public function sendCompletionNotification($hrEmail, $employeeName, $supervisorName)
    {
        $subject = "Evaluation Completed: {$employeeName}";
        
        $body = $this->getTemplate('completion_notification', [
            'employeeName' => $employeeName,
            'supervisorName' => $supervisorName,
            'timestamp' => date('F d, Y H:i A'),
        ]);

        return $this->send($hrEmail, $subject, $body);
    }

    /**
     * Send flagged employee alert
     */
    public function sendFlaggedEmployeeAlert($hrEmail, $employeeName, $score, $rating)
    {
        $subject = "ALERT: {$rating} Performance Rating - {$employeeName}";
        
        $body = $this->getTemplate('flagged_alert', [
            'employeeName' => $employeeName,
            'score' => $score,
            'rating' => $rating,
            'reviewUrl' => APP_URL . '/reports/individual/' . urlencode($employeeName),
        ]);

        return $this->send($hrEmail, $subject, $body);
    }

    /**
     * Send evaluation results to employee
     */
    public function sendEvaluationResults($employeeEmail, $employeeName, $finalScore, $rating)
    {
        $subject = "Your Performance Evaluation Results";
        
        $body = $this->getTemplate('evaluation_results', [
            'employeeName' => $employeeName,
            'finalScore' => $finalScore,
            'rating' => $rating,
            'portalUrl' => APP_URL . '/dashboard',
            'meetingDate' => date('F d, Y', strtotime('+5 days')),
        ]);

        return $this->send($employeeEmail, $subject, $body);
    }

    /**
     * Send evaluation reminder
     */
    public function sendReminder($email, $type, $daysUntilDeadline)
    {
        if ($type === 'supervisor') {
            $subject = "Reminder: Performance Evaluations Due in {$daysUntilDeadline} Days";
            $body = $this->getTemplate('supervisor_reminder', [
                'daysLeft' => $daysUntilDeadline,
                'formUrl' => APP_URL . '/evaluation/form',
            ]);
        } else {
            $subject = "Reminder: Peer Review Due in {$daysUntilDeadline} Days";
            $body = $this->getTemplate('peer_reminder', [
                'daysLeft' => $daysUntilDeadline,
            ]);
        }

        return $this->send($email, $subject, $body);
    }

    /**
     * Send email
     */
    private function send($recipientEmail, $subject, $body)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($recipientEmail);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            return $this->mailer->send();
        } catch (Exception $e) {
            $this->logError("Mail Error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Get email template
     */
    private function getTemplate($templateName, $variables = [])
    {
        $templatePath = VIEWS_PATH . '/emails/' . $templateName . '.php';
        
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Email template not found: {$templateName}");
        }

        extract($variables);
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Log email errors
     */
    private function logError($message)
    {
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0755, true);
        }
        error_log($message . PHP_EOL, 3, LOG_PATH . '/email.log');
    }
}
