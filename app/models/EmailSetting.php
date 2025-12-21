<?php
/**
 * Email Settings Model
 * Handles email configuration storage
 */
class EmailSetting extends Model {
    protected $table = 'email_settings';
    
    /**
     * Get email configuration
     */
    public function getConfig() {
        $result = $this->find(1);
        return $result;
    }
    
    /**
     * Update or create email configuration
     */
    public function updateConfig($data) {
        $config = $this->getConfig();
        
        // Add updated_at timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($config) {
            // Update existing - always return true if no SQL error
            try {
                $this->update(1, $data);
                // Even if rowCount is 0 (no change), it's successful
                return true;
            } catch (Exception $e) {
                error_log("Email config update failed: " . $e->getMessage());
                return false;
            }
        } else {
            // Create new with id = 1
            $data['id'] = 1;
            return $this->create($data);
        }
    }
    
    /**
     * Test email connection
     */
    public function testConnection($username, $password) {
        try {
            // Load PHPMailer if not already loaded
            if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                $composerAutoload = ROOT_PATH . '/vendor/autoload.php';
                if (file_exists($composerAutoload)) {
                    require_once $composerAutoload;
                } else {
                    require_once ROOT_PATH . '/vendor/phpmailer/src/Exception.php';
                    require_once ROOT_PATH . '/vendor/phpmailer/src/PHPMailer.php';
                    require_once ROOT_PATH . '/vendor/phpmailer/src/SMTP.php';
                }
            }
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Timeout = 10;
            
            // Just test connection, don't send
            $mail->SMTPDebug = 0;
            $result = $mail->smtpConnect();
            $mail->smtpClose();
            
            return $result;
        } catch (Exception $e) {
            error_log("SMTP test failed: " . $e->getMessage());
            return false;
        }
    }
}
