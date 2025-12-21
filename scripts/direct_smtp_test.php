<?php
/**
 * Direct SMTP Test with PHPMailer
 */

require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "=== DIRECT PHPMAILER SMTP TEST ===\n\n";

// Credentials dari user
$username = 'andirifqialnur276@gmail.com';
$password = 'wapkmbvrzbplywya'; // App Password baru
$to_email = 'zivanamount.dev@gmail.com';

echo "Testing with:\n";
echo "  Username: {$username}\n";
echo "  Password: " . str_repeat('*', strlen($password)) . " ({$password})\n";
echo "  To: {$to_email}\n\n";

try {
    $mail = new PHPMailer(true);
    
    // Enable verbose debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'echo';
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Recipients
    $mail->setFrom($username, 'Zivana School Test');
    $mail->addAddress($to_email);
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Direct SMTP - ' . date('H:i:s');
    $mail->Body = '<h1>Test Email</h1><p>Ini test langsung dari PHPMailer.</p>';
    
    echo "\n--- Attempting to send email ---\n\n";
    $mail->send();
    
    echo "\n\n✓ SUCCESS! Email has been sent to {$to_email}\n";
    echo "Check your inbox (and SPAM folder)\n";
    
} catch (Exception $e) {
    echo "\n\n✗ FAILED: {$mail->ErrorInfo}\n";
    echo "Exception: {$e->getMessage()}\n";
}
