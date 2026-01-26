<?php
/**
 * User Management Tab Component
 * Displays admin users management for settings page
 */

// Include delete confirmation modal
component('widget/modal-delete-confirmation', [
    'modalId' => 'modal-delete-user',
    'title' => 'Hapus User Admin',
    'description' => 'Apakah Anda yakin ingin menghapus user admin ini? Tindakan ini tidak dapat dibatalkan.'
]);

// Include modals
require VIEW_PATH . '/components/widget/setting/modal/modal-add-user.php';
require VIEW_PATH . '/components/widget/setting/modal/modal-edit-user.php';

// Fetch users from database
try {
    $userModel = new User();
    $users = $userModel->all();
} catch (Exception $e) {
    error_log("Error loading users: " . $e->getMessage());
    $users = [];
}

// Build user cards HTML for slot
ob_start();
?>
<div id="user-list" class="mt-5 space-y-3">
    <?php if (empty($users)): ?>
        <div class="text-center py-8 text-white-soft">
            <p>Belum ada user admin</p>
        </div>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <div class="user-item rounded-[12px] py-[16px] px-[20px] transition-all flex items-center justify-between" 
                 data-id="<?= $user['id'] ?>" 
                 data-username="<?= e($user['username']) ?>" 
                 data-email="<?= e($user['email']) ?>" 
                 data-full-name="<?= e($user['full_name']) ?>"
                 data-role="<?= e($user['role']) ?>"
                 data-is-active="<?= $user['is_active'] ?>"
                 style="background-color: <?= colors('card_bg_light') ?>">
                
                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="font-bold text-[14px] text-black-neutral"><?= e($user['full_name']) ?></h3>
                        <?php if ($user['role'] === 'super_admin'): ?>
                            <span class="px-2 py-1 text-[10px] font-semibold rounded" style="background: <?= colors('primary') ?>; color: white;">SUPER ADMIN</span>
                        <?php endif; ?>
                        <?php if (!$user['is_active']): ?>
                            <span class="px-2 py-1 text-[10px] font-semibold rounded" style="background: #e74c3c; color: white;">NON-AKTIF</span>
                        <?php endif; ?>
                    </div>
                    <p class="font-normal text-[12px] text-black-highlight">
                        <?= e($user['username']) ?> • <?= e($user['email']) ?>
                    </p>
                    <?php if ($user['last_login']): ?>
                        <p class="font-normal text-[11px] text-white-soft mt-1">
                            Login terakhir: <?= date('d M Y H:i', strtotime($user['last_login'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button class="edit-user-btn p-2 rounded hover:bg-opacity-80 transition" 
                            style="background-color: <?= colors('primary') ?>;"
                            title="Edit User">
                        <svg class="w-4 h-4" fill="white" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                    </button>
                    <button class="delete-user-btn p-2 rounded hover:bg-opacity-80 transition" 
                            style="background-color: #e74c3c;"
                            title="Hapus User">
                        <svg class="w-4 h-4" fill="white" viewBox="0 0 24 24">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
$userCardsHtml = ob_get_clean();

// Render tab content card with users in slot
component('tab-content-card', [
    'title' => 'Manajemen User Admin',
    'description' => 'Kelola user admin yang dapat mengakses dashboard',
    'buttonText' => 'Tambah User Admin',
    'buttonId' => 'btn-add-user',
    'buttonIcon' => 'plus',
    'slot' => $userCardsHtml
]);
?>

<!-- User Management Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add User button
    const btnAddUser = document.getElementById('btn-add-user');
    if (btnAddUser) {
        btnAddUser.addEventListener('click', function() {
            const modal = document.getElementById('modal-add-user');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    }
    
    // Edit User buttons
    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const userItem = this.closest('.user-item');
            const modal = document.getElementById('modal-edit-user');
            
            if (modal && userItem) {
                // Populate modal with user data
                document.getElementById('edit-user-id').value = userItem.dataset.id;
                document.getElementById('edit-username').value = userItem.dataset.username;
                document.getElementById('edit-email').value = userItem.dataset.email;
                document.getElementById('edit-full-name').value = userItem.dataset.fullName;
                document.getElementById('edit-role').value = userItem.dataset.role;
                document.getElementById('edit-is-active').checked = userItem.dataset.isActive === '1';
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    // Delete User buttons
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const userItem = this.closest('.user-item');
            
            if (userItem) {
                const userId = userItem.dataset.id;
                const userName = userItem.dataset.fullName;
                
                // Open delete confirmation modal
                openModalDeleteUser(
                    'Hapus User Admin',
                    `Apakah Anda yakin ingin menghapus user "${userName}"? Tindakan ini tidak dapat dibatalkan.`,
                    async function() {
                        try {
                            const formData = new FormData();
                            formData.append('id', userId);
                            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
                            
                            const response = await fetch('<?= url('/admin/settings/users/delete') ?>', {
                                method: 'POST',
                                body: formData
                            });
                            
                            const result = await response.json();
                            
                            if (result.success) {
                                showToast('User berhasil dihapus', 'success', 3000);
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                showToast(result.message || 'Gagal menghapus user', 'error', 5000);
                            }
                        } catch (error) {
                            showToast('Terjadi kesalahan: ' + error.message, 'error', 5000);
                        }
                    }
                );
            }
        });
    });
});
</script>
