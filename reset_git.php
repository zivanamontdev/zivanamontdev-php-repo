<?php
/**
 * Reset Git Repository - Discard local changes in vendor/
 * Akses via: https://yourdomain.com/reset_git.php
 * HAPUS FILE INI setelah selesai digunakan!
 */

echo "<h2>🔧 Git Repository Reset Tool</h2>";
echo "<p>Script ini akan reset vendor/ directory ke state dari Git</p>";
echo "<hr>";

// Change to project root directory
chdir(__DIR__);

// Check if git is available
exec('git --version 2>&1', $output, $returnCode);
if ($returnCode !== 0) {
    echo "<p style='color: red;'>❌ Git tidak tersedia di server ini</p>";
    exit;
}

echo "<h3>📋 Status Sebelum Reset:</h3>";
echo "<pre>";
exec('git status', $statusBefore);
echo implode("\n", $statusBefore);
echo "</pre>";
echo "<hr>";

// Reset vendor/ directory
echo "<h3>🔄 Mereset vendor/ directory...</h3>";
exec('git checkout -- vendor/ 2>&1', $resetOutput, $resetCode);

if ($resetCode === 0) {
    echo "<p style='color: green;'>✅ Vendor directory berhasil direset!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Reset output:</p>";
    echo "<pre>" . implode("\n", $resetOutput) . "</pre>";
}

// Clean untracked files in vendor/
echo "<h3>🧹 Membersihkan untracked files...</h3>";
exec('git clean -fd vendor/ 2>&1', $cleanOutput, $cleanCode);

if ($cleanCode === 0) {
    echo "<p style='color: green;'>✅ Untracked files berhasil dibersihkan!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Clean output:</p>";
    echo "<pre>" . implode("\n", $cleanOutput) . "</pre>";
}

echo "<hr>";
echo "<h3>📋 Status Setelah Reset:</h3>";
echo "<pre>";
exec('git status', $statusAfter);
echo implode("\n", $statusAfter);
echo "</pre>";

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ PENTING: Hapus file reset_git.php ini setelah selesai!</p>";
echo "<p>Sekarang coba deploy ulang dari dashboard hosting Anda.</p>";
?>
