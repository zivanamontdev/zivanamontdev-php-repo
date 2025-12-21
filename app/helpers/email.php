<?php
/**
 * Email Helper
 * Uses PHPMailer for sending emails via Gmail SMTP
 */

// Load PHPMailer - check if composer autoloader exists first
$composerAutoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
} else {
    // Manual loading as fallback
    require_once __DIR__ . '/../../vendor/phpmailer/src/Exception.php';
    require_once __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send email via Gmail SMTP or log to file (local)
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email message (HTML)
 * @return bool Success status
 */
function send_email($to, $subject, $message) {
    $emailConfig = require __DIR__ . '/../../config/email.php';
    
    // If local environment, log to file instead of sending
    if ($emailConfig['environment'] === 'local') {
        return log_email_to_file($to, $subject, $message);
    }
    
    // Production: Send via Gmail SMTP
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $emailConfig['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig['smtp_username'];
        $mail->Password = $emailConfig['smtp_password'];
        $mail->SMTPSecure = $emailConfig['smtp_encryption'];
        $mail->Port = $emailConfig['smtp_port'];
        
        // Recipients
        $mail->setFrom($emailConfig['from_email'], $emailConfig['from_name']);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';
        
        // Send
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        // Log error
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Log email to file (local development)
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email message (HTML)
 * @return bool Success status
 */
function log_email_to_file($to, $subject, $message) {
    $logDir = __DIR__ . '/../../storage/logs';
    $logFile = $logDir . '/emails.log';
    
    // Create logs directory if it doesn't exist
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $separator = str_repeat('=', 80);
    
    $logContent = "\n{$separator}\n";
    $logContent .= "TIMESTAMP: {$timestamp}\n";
    $logContent .= "TO: {$to}\n";
    $logContent .= "SUBJECT: {$subject}\n";
    $logContent .= "MESSAGE:\n{$message}\n";
    $logContent .= "{$separator}\n";
    
    // Log to file
    file_put_contents($logFile, $logContent, FILE_APPEND);
    
    return true;
}

/**
 * Generate reset password token
 * 
 * @return string Random token
 */
function generate_reset_token() {
    return bin2hex(random_bytes(32));
}

/**
 * Send password reset email
 * 
 * @param string $email User email
 * @param string $token Reset token
 * @return bool Success status
 */
function send_password_reset_email($email, $token) {
    $resetLink = url("/admin/reset-password?token={$token}");
    
    $subject = "Reset Password - " . APP_NAME;
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2 style='color: #C92C2F;'>Reset Password</h2>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
            <p>Klik tombol di bawah untuk reset password Anda:</p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}' style='background-color: #C92C2F; color: white; padding: 12px 24px; text-decoration: none; border-radius: 12px; display: inline-block; font-weight: bold;'>Reset Password</a>
            </div>
            <p>Atau copy link berikut ke browser Anda:</p>
            <p style='word-break: break-all; color: #666;'>{$resetLink}</p>
            <p style='color: #999; font-size: 12px; margin-top: 30px;'>Link ini akan kadaluarsa dalam 1 jam.</p>
            <p style='color: #999; font-size: 12px;'>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        </div>
    </body>
    </html>
    ";
    
    return send_email($email, $subject, $message);
}
