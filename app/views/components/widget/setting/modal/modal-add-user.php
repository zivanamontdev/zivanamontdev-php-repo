<?php
/**
 * Add User Modal Component
 */
?>

<!-- Add User Modal -->
<div id="modal-add-user" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-[24px] p-8 w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold" style="color: <?= colors('primary') ?>;">Tambah User Admin</h2>
            <button type="button" class="close-modal text-gray-500 hover:text-gray-700 transition" data-modal="modal-add-user">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="form-add-user" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-username">Username *</label>
                <input type="text" 
                       id="add-username" 
                       name="username" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                       placeholder="username"
                       required>
                <span class="text-xs text-gray-500 mt-1 block">Username untuk login (tanpa spasi)</span>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-email">Email *</label>
                <input type="email" 
                       id="add-email" 
                       name="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                       placeholder="admin@example.com"
                       required>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-full-name">Nama Lengkap *</label>
                <input type="text" 
                       id="add-full-name" 
                       name="full_name" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                       placeholder="John Doe"
                       required>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-password">Password *</label>
                <input type="password" 
                       id="add-password" 
                       name="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                       placeholder="Minimal 8 karakter"
                       required
                       minlength="8">
                <span class="text-xs text-gray-500 mt-1 block">Minimal 8 karakter</span>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-password-confirm">Konfirmasi Password *</label>
                <input type="password" 
                       id="add-password-confirm" 
                       name="password_confirm" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                       placeholder="Ketik ulang password"
                       required
                       minlength="8">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2" for="add-role">Role *</label>
                <select id="add-role" 
                        name="role" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition"
                        required>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           id="add-is-active" 
                           name="is_active" 
                           value="1"
                           checked
                           class="w-4 h-4">
                    <span class="font-semibold text-gray-700">User Aktif</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 py-3 px-6 rounded-lg font-semibold text-white transition hover:opacity-90"
                        style="background-color: <?= colors('primary') ?>;">
                    Simpan User
                </button>
                <button type="button" 
                        class="close-modal px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition"
                        data-modal="modal-add-user">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-add-user');
    const modal = document.getElementById('modal-add-user');
    
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('add-password').value;
            const passwordConfirm = document.getElementById('add-password-confirm').value;
            
            if (password !== passwordConfirm) {
                showToast('Password tidak cocok!', 'error', 3000);
                return;
            }
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            try {
                const response = await fetch('<?= url('/admin/settings/users/create') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('User berhasil ditambahkan!', 'success', 3000);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message || 'Gagal menambahkan user', 'error', 5000);
                }
            } catch (error) {
                showToast('Terjadi kesalahan: ' + error.message, 'error', 5000);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
    
    // Close modal handlers
    document.querySelectorAll('.close-modal[data-modal="modal-add-user"]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                form.reset();
            }
        });
    });
    
    // Close on backdrop click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                form.reset();
            }
        });
    }
});
</script>
