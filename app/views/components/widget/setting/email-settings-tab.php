<?php
/**
 * Email Settings Tab Component
 * Configure SMTP email settings
 */

// Fetch email config from database
try {
    $emailSettingModel = new EmailSetting();
    $emailConfig = $emailSettingModel->getConfig();
    
    // If no config found, use defaults
    if (!$emailConfig) {
        $emailConfig = [
            'is_enabled' => false,
            'smtp_username' => '',
            'smtp_password' => ''
        ];
    }
} catch (Exception $e) {
    error_log("Error loading email settings: " . $e->getMessage());
    $emailConfig = [
        'is_enabled' => false,
        'smtp_username' => '',
        'smtp_password' => ''
    ];
}

ob_start();
?>
<div class="rounded-[24px] p-8" style="background-color: <?= colors('card_bg_light') ?>;">
    <div class="info-box mb-6" style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 12px; padding: 16px;">
        <h4 style="color: #1976d2; margin-bottom: 8px; font-size: 16px; font-weight: 600;">📧 Cara Setup Gmail SMTP:</h4>
        <p style="color: #555; margin: 0; font-size: 14px; line-height: 1.6;">
            1. Buka <a href="https://myaccount.google.com/security" target="_blank" style="color: #1976d2; text-decoration: underline;">Google Account Security</a><br>
            2. Aktifkan <strong>2-Step Verification</strong><br>
            3. Buat <strong>App Password</strong> (16 karakter)<br>
            4. Masukkan email Gmail dan App Password di bawah<br>
            5. Klik <strong>Simpan & Test Koneksi</strong>
        </p>
    </div>

    <form id="emailSettingsForm">
        <?= csrf_field() ?>
        
        <div class="mb-6">
            <div style="display: flex; align-items: center; gap: 12px;">
                <label class="switch">
                    <input type="checkbox" 
                           id="is_enabled" 
                           name="is_enabled" 
                           value="1"
                           <?= isset($emailConfig) && $emailConfig['is_enabled'] ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
                <label for="is_enabled" class="font-semibold text-black-neutral" style="margin: 0; cursor: pointer;">
                    Aktifkan Email SMTP
                </label>
            </div>
            <span class="text-[12px] text-white-soft block mt-2">Jika dinonaktifkan, email akan di-log ke file</span>
        </div>

        <div class="mb-6">
            <label class="block font-semibold text-black-neutral mb-2" for="smtp_username">Email Gmail</label>
            <input type="email" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-primary transition"
                   id="smtp_username" 
                   name="smtp_username" 
                   placeholder="contoh: sekolah@gmail.com"
                   value="<?= e($emailConfig['smtp_username'] ?? '') ?>">
            <span class="text-[12px] text-white-soft block mt-2">Email Gmail yang akan digunakan untuk mengirim email</span>
        </div>

        <div class="mb-6">
            <label class="block font-semibold text-black-neutral mb-2" for="smtp_password">App Password (16 karakter)</label>
            <input type="password" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-primary transition"
                   id="smtp_password" 
                   name="smtp_password" 
                   placeholder="abcd efgh ijkl mnop"
                   value="<?= e($emailConfig['smtp_password'] ?? '') ?>">
            <span class="text-[12px] text-white-soft block mt-2">
                BUKAN password Gmail biasa! Harus App Password dari Google. 
                <a href="https://support.google.com/accounts/answer/185833" target="_blank" style="color: <?= colors('primary') ?>; text-decoration: underline;">Panduan lengkap →</a>
            </span>
        </div>

        <div class="mb-6">
            <label class="block font-semibold text-black-neutral mb-2" for="test_email">Test Email</label>
            <input type="email" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-primary transition"
                   id="test_email" 
                   name="test_email" 
                   placeholder="contoh: admin@example.com"
                   value="">
            <span class="text-[12px] text-white-soft block mt-2">💡 Jika diisi, test email akan otomatis dikirim setelah menyimpan konfigurasi</span>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" 
                    class="px-6 py-3 rounded-lg font-semibold text-white transition hover:opacity-90"
                    style="background-color: <?= colors('primary') ?>;">
                💾 Simpan & Kirim Test Email
            </button>
        </div>
    </form>
</div>

<style>
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: <?= colors('primary') ?>;
}

input:checked + .slider:before {
    transform: translateX(26px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('emailSettingsForm');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const testEmail = document.getElementById('test_email').value;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = testEmail ? '⏳ Menyimpan & Mengirim...' : '⏳ Menyimpan...';
            
            try {
                const response = await fetch('<?= url('/admin/settings/email/update') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success', 5000);
                } else {
                    showToast(result.message, 'error', 5000);
                }
            } catch (error) {
                showToast('Terjadi kesalahan: ' + error.message, 'error', 5000);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>
<?php
$emailSettingsHtml = ob_get_clean();

// Render simple container (no card wrapper needed since we have styling above)
echo $emailSettingsHtml;
?>
