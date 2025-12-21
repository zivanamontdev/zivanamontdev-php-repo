<?php
/**
 * Email Log Viewer
 * View logged emails when SMTP is not configured
 * 
 * Access: /scripts/view_email_log.php
 * 
 * Security: Only accessible in development environment
 */

require_once __DIR__ . '/../config/config.php';

// Only allow in development or if debug is enabled
if (APP_ENV === 'production' && !APP_DEBUG) {
    die('Access denied');
}

// Get log file path
$logFile = __DIR__ . '/../storage/logs/emails.log';

// Check if log file exists
if (!file_exists($logFile)) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Email Log Viewer</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            h1 { color: #C92C2F; }
            .no-emails { padding: 20px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; color: #856404; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>📧 Email Log Viewer</h1>
            <div class='no-emails'>
                <strong>No emails logged yet.</strong><br>
                File: storage/logs/emails.log tidak ditemukan.
            </div>
        </div>
    </body>
    </html>";
    exit;
}

// Read log file
$logContent = file_get_contents($logFile);

// Parse emails from log
$emails = [];
$entries = explode('================================================================================', $logContent);

foreach ($entries as $entry) {
    if (trim($entry) === '') continue;
    
    $email = [];
    
    // Extract timestamp
    if (preg_match('/TIMESTAMP: (.+)/', $entry, $matches)) {
        $email['timestamp'] = trim($matches[1]);
    }
    
    // Extract TO
    if (preg_match('/TO: (.+)/', $entry, $matches)) {
        $email['to'] = trim($matches[1]);
    }
    
    // Extract SUBJECT
    if (preg_match('/SUBJECT: (.+)/', $entry, $matches)) {
        $email['subject'] = trim($matches[1]);
    }
    
    // Extract MESSAGE
    if (preg_match('/MESSAGE:\s*(.+)/s', $entry, $matches)) {
        $email['message'] = trim($matches[1]);
        
        // Extract reset link if exists
        if (preg_match('/href=[\'"]([^\'"]+reset-password[^\'"]+)[\'"]/i', $email['message'], $linkMatches)) {
            $email['reset_link'] = $linkMatches[1];
        }
    }
    
    if (!empty($email['to'])) {
        $emails[] = $email;
    }
}

// Reverse to show newest first
$emails = array_reverse($emails);

// Display
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Log Viewer</title>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; 
            padding: 20px; 
            background: #f5f5f5; 
            line-height: 1.6;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 { 
            color: #C92C2F; 
            margin-bottom: 10px;
        }
        .info {
            color: #666;
            font-size: 14px;
        }
        .info code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .email-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .email-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .email-meta {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .email-meta-label {
            font-weight: 600;
            color: #666;
        }
        .email-meta-value {
            color: #333;
        }
        .reset-link {
            background: #e8f5e9;
            border: 1px solid #4caf50;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .reset-link-label {
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 8px;
        }
        .reset-link-url {
            word-break: break-all;
            color: #1976d2;
            font-family: monospace;
            font-size: 13px;
            background: white;
            padding: 10px;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #C92C2F;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 14px;
        }
        .btn:hover {
            background: #a52428;
        }
        .message-preview {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 13px;
        }
        .message-preview iframe {
            width: 100%;
            min-height: 400px;
            border: none;
        }
        .no-emails {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .no-emails-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Email Log Viewer</h1>
            <p class="info">
                Emails are logged to <code>storage/logs/emails.log</code> when SMTP is not configured.<br>
                Total emails logged: <strong><?= count($emails) ?></strong>
            </p>
        </div>

        <?php if (empty($emails)): ?>
            <div class="no-emails">
                <div class="no-emails-icon">📭</div>
                <h2>No Emails Yet</h2>
                <p>No emails have been logged. Try the forgot password feature to generate an email.</p>
            </div>
        <?php else: ?>
            <?php foreach ($emails as $email): ?>
            <div class="email-card">
                <div class="email-header">
                    <div class="email-meta">
                        <span class="email-meta-label">To:</span>
                        <span class="email-meta-value"><?= htmlspecialchars($email['to']) ?></span>
                    </div>
                    <div class="email-meta">
                        <span class="email-meta-label">Subject:</span>
                        <span class="email-meta-value"><?= htmlspecialchars($email['subject']) ?></span>
                    </div>
                    <div class="email-meta">
                        <span class="email-meta-label">Time:</span>
                        <span class="email-meta-value"><?= htmlspecialchars($email['timestamp']) ?></span>
                    </div>
                </div>

                <?php if (!empty($email['reset_link'])): ?>
                <div class="reset-link">
                    <div class="reset-link-label">🔗 Reset Password Link:</div>
                    <div class="reset-link-url"><?= htmlspecialchars($email['reset_link']) ?></div>
                    <a href="<?= htmlspecialchars($email['reset_link']) ?>" class="btn" target="_blank">
                        Open Reset Link →
                    </a>
                </div>
                <?php endif; ?>

                <details>
                    <summary style="cursor: pointer; color: #666; margin-top: 15px; font-size: 14px;">
                        View Full Email Content
                    </summary>
                    <div class="message-preview" style="margin-top: 15px;">
                        <?= $email['message'] ?>
                    </div>
                </details>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
