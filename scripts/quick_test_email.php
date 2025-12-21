<?php
/**
 * Quick Email Test
 * Run this after 20 minutes when App Password is active
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/helpers/email.php';

echo "\n";
echo "╔════════════════════════════════════════════════╗\n";
echo "║     QUICK EMAIL TEST - ZIVANA SCHOOL           ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

$to = 'andirifqialnur276@gmail.com';
$subject = 'Test Email Zivana - ' . date('H:i:s');
$message = '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #C92C2F;">🎉 Email SMTP Berhasil!</h2>
    <p>Selamat! Sistem email SMTP sudah berfungsi dengan baik.</p>
    <p><strong>Waktu pengiriman:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <hr style="border: 1px solid #eee; margin: 20px 0;">
    <p style="color: #666; font-size: 12px;">
        Email ini dikirim dari sistem Zivana Montessori School
        menggunakan Gmail SMTP (zivanamont.dev@gmail.com)
    </p>
</div>
';

echo "📧 Mengirim test email ke: {$to}\n";
echo "📝 Subject: {$subject}\n\n";
echo "⏳ Sedang mengirim...\n\n";

$result = send_email($to, $subject, $message);

if ($result) {
    echo "╔════════════════════════════════════════════════╗\n";
    echo "║              ✓ EMAIL BERHASIL DIKIRIM!        ║\n";
    echo "╚════════════════════════════════════════════════╝\n\n";
    echo "✅ Email telah dikirim ke: {$to}\n";
    echo "📬 Silahkan cek:\n";
    echo "   1. Inbox email Anda\n";
    echo "   2. Folder SPAM (jika tidak ada di inbox)\n";
    echo "   3. Folder Promotions (Gmail)\n\n";
    echo "💡 Tips: Jika ada di SPAM, klik 'Not Spam' agar\n";
    echo "   email berikutnya masuk ke Inbox.\n\n";
} else {
    echo "╔════════════════════════════════════════════════╗\n";
    echo "║              ✗ EMAIL GAGAL DIKIRIM            ║\n";
    echo "╚════════════════════════════════════════════════╝\n\n";
    echo "❌ Kemungkinan penyebab:\n";
    echo "   1. App Password belum aktif (tunggu 5-10 menit lagi)\n";
    echo "   2. Password salah\n";
    echo "   3. Koneksi internet bermasalah\n\n";
    echo "📋 Cek log error: storage/logs/emails.log\n\n";
}
