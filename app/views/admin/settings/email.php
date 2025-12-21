<?php
$pageTitle = 'Email Settings';
ob_start();
?>

<style>
    .settings-card {
        background: white;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .settings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: <?= colors('primary') ?>;
    }
    
    .form-help {
        display: block;
        font-size: 13px;
        color: #666;
        margin-top: 6px;
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: <?= colors('primary') ?>;
        color: white;
    }
    
    .btn-primary:hover {
        opacity: 0.9;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .info-box {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }
    
    .info-box h4 {
        color: #1976d2;
        margin-bottom: 8px;
        font-size: 16px;
    }
    
    .info-box p {
        color: #555;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .info-box a {
        color: #1976d2;
        text-decoration: underline;
    }
    
    .switch-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
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

<div class="container mx-auto mt-8">
    <div class="settings-card">
        <div class="settings-header">
            <div>
                <h1 class="text-2xl font-bold" style="color: <?= colors('primary') ?>;">⚙️ Pengaturan Email</h1>
                <p class="text-gray-600 mt-1">Konfigurasi SMTP untuk mengirim email reset password</p>
            </div>
            <a href="<?= url('/admin/settings') ?>" class="btn btn-secondary">← Kembali</a>
        </div>

        <div class="info-box">
            <h4>📧 Cara Setup Gmail SMTP:</h4>
            <p>
                1. Buka <a href="https://myaccount.google.com/security" target="_blank">Google Account Security</a><br>
                2. Aktifkan <strong>2-Step Verification</strong><br>
                3. Buat <strong>App Password</strong> (16 karakter)<br>
                4. Masukkan email Gmail dan App Password di bawah<br>
                5. Klik <strong>Simpan & Test Koneksi</strong>
            </p>
        </div>

        <form id="emailSettingsForm">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" 
                               id="is_enabled" 
                               name="is_enabled" 
                               value="1"
                               <?= isset($emailConfig) && $emailConfig['is_enabled'] ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                    <label for="is_enabled" class="form-label" style="margin: 0; cursor: pointer;">
                        Aktifkan Email SMTP
                    </label>
                </div>
                <span class="form-help">Jika dinonaktifkan, email akan di-log ke file</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="smtp_username">Email Gmail</label>
                <input type="email" 
                       class="form-control" 
                       id="smtp_username" 
                       name="smtp_username" 
                       placeholder="contoh: sekolah@gmail.com"
                       value="<?= e($emailConfig['smtp_username'] ?? '') ?>">
                <span class="form-help">Email Gmail yang akan digunakan untuk mengirim email</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="smtp_password">App Password (16 karakter)</label>
                <input type="password" 
                       class="form-control" 
                       id="smtp_password" 
                       name="smtp_password" 
                       placeholder="abcd efgh ijkl mnop"
                       value="<?= e($emailConfig['smtp_password'] ?? '') ?>">
                <span class="form-help">
                    BUKAN password Gmail biasa! Harus App Password dari Google. 
                    <a href="https://support.google.com/accounts/answer/185833" target="_blank">Panduan lengkap →</a>
                </span>
            </div>

            <div class="form-group">
                <label class="form-label" for="test_email">Test Email (opsional)</label>
                <input type="email" 
                       class="form-control" 
                       id="test_email" 
                       name="test_email" 
                       placeholder="Masukkan email untuk test">
                <span class="form-help">Kirim email test setelah menyimpan konfigurasi</span>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">💾 Simpan & Test Koneksi</button>
                <button type="button" id="sendTestEmail" class="btn btn-secondary" style="display: none;">
                    📧 Kirim Test Email
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('emailSettingsForm');
    const sendTestBtn = document.getElementById('sendTestEmail');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Menyimpan & Testing...';
        
        try {
            const response = await fetch('<?= url('/admin/settings/email/update') ?>', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('success', result.message);
                sendTestBtn.style.display = 'inline-block';
            } else {
                showToast('error', result.message);
            }
        } catch (error) {
            showToast('error', 'Terjadi kesalahan: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
    
    sendTestBtn.addEventListener('click', async function() {
        const testEmail = document.getElementById('test_email').value;
        
        if (!testEmail) {
            showToast('error', 'Masukkan email untuk test terlebih dahulu');
            return;
        }
        
        const formData = new FormData();
        formData.append('test_email', testEmail);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        
        sendTestBtn.disabled = true;
        sendTestBtn.innerHTML = '📨 Mengirim...';
        
        try {
            const response = await fetch('<?= url('/admin/settings/email/test') ?>', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('success', result.message);
            } else {
                showToast('error', result.message);
            }
        } catch (error) {
            showToast('error', 'Terjadi kesalahan: ' + error.message);
        } finally {
            sendTestBtn.disabled = false;
            sendTestBtn.innerHTML = '📧 Kirim Test Email';
        }
    });
    
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: ${type === 'success' ? '#4caf50' : '#f44336'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
