<?php
/**
 * Test Forget Password Flow
 * Simulates the complete password reset process
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Model.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/email.php';
require_once ROOT_PATH . '/app/helpers/functions.php';

echo "\n";
echo "╔═══════════════════════════════════════════════════╗\n";
echo "║   TEST FORGET PASSWORD - ZIVANA SCHOOL            ║\n";
echo "╚═══════════════════════════════════════════════════╝\n\n";

// 1. Check user exists
$testEmail = 'andirifqialnur276@gmail.com'; // Email untuk test
echo "📝 Step 1: Checking if user exists in database...\n";
$userModel = new User();
$user = $userModel->findByEmail($testEmail);

if (!$user) {
    echo "   ⚠️  User not found in database\n";
    echo "   Creating test user...\n\n";
    
    // Create test user if not exists (for testing only)
    echo "   ℹ️  For this test to work, make sure admin user exists\n";
    echo "   Run: php scripts/add_user.php\n\n";
    exit(1);
}

echo "   ✓ User found: {$user['username']} ({$user['email']})\n\n";

// 2. Generate reset token
echo "📧 Step 2: Generating password reset token...\n";
$token = bin2hex(random_bytes(32));
$expiry = time() + 3600; // 1 hour

echo "   Token: {$token}\n";
echo "   Expires: " . date('Y-m-d H:i:s', $expiry) . "\n\n";

// 3. Build reset link
$resetLink = url('/admin/reset-password?token=' . $token);
echo "🔗 Step 3: Reset link generated\n";
echo "   Link: {$resetLink}\n\n";

// 4. Send email
echo "📨 Step 4: Sending password reset email...\n";
echo "   From: zivanamont.dev@gmail.com\n";
echo "   To: {$testEmail}\n";
echo "   Subject: Reset Password - Zivana Montessori School\n\n";

$subject = 'Reset Password - Zivana Montessori School';
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

echo "⏳ Sending email...\n\n";
$result = send_email($testEmail, $subject, $message);

if ($result) {
    echo "╔═══════════════════════════════════════════════════╗\n";
    echo "║          ✓ PASSWORD RESET EMAIL SENT!            ║\n";
    echo "╚═══════════════════════════════════════════════════╝\n\n";
    echo "✅ Email berhasil dikirim ke: {$testEmail}\n\n";
    echo "📬 Silahkan cek email:\n";
    echo "   1. Inbox (Primary)\n";
    echo "   2. Folder SPAM\n";
    echo "   3. Folder Promotions (Gmail)\n\n";
    echo "🔗 Reset Link:\n";
    echo "   {$resetLink}\n\n";
    echo "💡 Tips:\n";
    echo "   - Link valid selama 1 jam\n";
    echo "   - Jika email di SPAM, mark as 'Not Spam'\n";
    echo "   - Copy link dari email dan paste ke browser\n\n";
    
    // Store token in session for testing
    session_start();
    if (!isset($_SESSION['reset_tokens'])) {
        $_SESSION['reset_tokens'] = [];
    }
    $_SESSION['reset_tokens'][$token] = [
        'email' => $testEmail,
        'user_id' => $user['id'],
        'expires' => $expiry
    ];
    
    echo "💾 Token stored in session for testing\n\n";
    
} else {
    echo "╔═══════════════════════════════════════════════════╗\n";
    echo "║          ✗ FAILED TO SEND EMAIL                  ║\n";
    echo "╚═══════════════════════════════════════════════════╝\n\n";
    echo "❌ Email gagal dikirim\n";
    echo "📋 Check: storage/logs/emails.log\n\n";
}

echo "═══════════════════════════════════════════════════\n";
echo "TEST COMPLETED\n";
echo "═══════════════════════════════════════════════════\n\n";
