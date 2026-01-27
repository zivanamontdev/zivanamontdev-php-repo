<?php
/**
 * Settings Page
 * Page for managing school settings
 */

$pageTitle = 'Pengaturan';
$currentPage = 'settings';

// Start output buffering
ob_start();
?>

<!-- Admin Navbar -->
<?php component('admin-navbar', ['title' => 'Pengaturan']); ?>

<!-- Tabs with Reset Database Button -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
    <!-- Tabs -->
    <div style="flex: 1;">
        <?php component('tabs', [
            'tabs' => [
                'Pendaftaran',
                'Highlight Program',
                'Testimoni',
                'Events',
                'FAQ',
                'User Admin',
                'Pengaturan Email'
            ],
            'active' => 0
        ]); ?>
    </div>
    
    <!-- Reset Database Button -->
    <button id="btn-reset-database" 
            style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; margin-left: 16px; white-space: nowrap;"
            onmouseover="this.style.opacity='0.9'" 
            onmouseout="this.style.opacity='1'">
        Reset Database
    </button>
</div>

<!-- Tab Content Panels -->
<div class="mt-3">
    <!-- Tab 1: Pendaftaran -->
    <div id="tab-panel-0" class="tab-panel">
        <?php require VIEW_PATH . '/components/widget/setting/setting-register-tab.php'; ?>
    </div>
    
    <!-- Tab 2: Highlight Program -->
    <div id="tab-panel-1" class="tab-panel hidden">
        <?php component('widget/setting/highlight-program-sekolah-tab'); ?>
    </div>
    
    <!-- Tab 3: Testimoni -->
    <div id="tab-panel-2" class="tab-panel hidden">
        <?php component('widget/setting/highlight-testimoni-tab'); ?>
    </div>
    
    <!-- Tab 4: Events -->
    <div id="tab-panel-3" class="tab-panel hidden">
        <?php component('widget/setting/event-tab'); ?>
    </div>
    
    <!-- Tab 5: FAQ -->
    <div id="tab-panel-4" class="tab-panel hidden">
        <?php component('widget/setting/faq-tab'); ?>
    </div>
    
    <!-- Tab 6: User Admin -->
    <div id="tab-panel-5" class="tab-panel hidden">
        <?php component('widget/setting/user-management-tab'); ?>
    </div>
    
    <!-- Tab 7: Pengaturan Email -->
    <div id="tab-panel-6" class="tab-panel hidden">
        <?php component('widget/setting/email-settings-tab'); ?>
    </div>
</div>

<!-- SortableJS Library (Loaded once for all tabs) -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Reset Database Modal -->
<div id="modal-reset-database" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-[24px] p-8 w-full max-w-lg shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold" style="color: #e74c3c;">⚠️ Reset Database</h2>
            <button type="button" id="close-reset-modal" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-red-800 font-semibold mb-2">⚠️ PERINGATAN KERAS!</p>
                <p class="text-red-700 text-sm">Tindakan ini akan menghapus <strong>SEMUA DATA</strong> dari database kecuali:</p>
                <ul class="list-disc list-inside text-red-700 text-sm mt-2 space-y-1">
                    <li>Data user admin</li>
                    <li>Pengaturan sistem</li>
                    <li>Pengaturan email</li>
                    <li>Form fields pendaftaran</li>
                    <li>Pengaturan pendaftaran</li>
                    <li>Field pendaftaran</li>
                    <li>Program harian & galerinya</li>
                    <li>Testimoni</li>
                    <li>Prakata</li>
                </ul>
                <p class="text-red-800 font-bold text-sm mt-3">Tindakan ini TIDAK DAPAT DIBATALKAN!</p>
            </div>

            <form id="form-reset-database">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700 mb-2" for="reset-password">
                        Password Admin untuk Konfirmasi *
                    </label>
                    <input type="password" 
                           id="reset-password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition"
                           placeholder="Masukkan password Anda"
                           required>
                    <span class="text-xs text-gray-500 mt-1 block">Ketik password admin Anda untuk melanjutkan</span>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-700 mb-2">
                        Ketik "RESET DATABASE" untuk konfirmasi *
                    </label>
                    <input type="text" 
                           id="reset-confirmation" 
                           name="confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition"
                           placeholder="RESET DATABASE"
                           required>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            id="cancel-reset" 
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Batalkan
                    </button>
                    <button type="submit" 
                            class="flex-1 py-3 px-6 rounded-lg font-semibold text-white transition hover:opacity-90"
                            style="background-color: #e74c3c;">
                        Ya, Reset Database
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Final Confirmation Modal -->
<div id="modal-final-confirm" class="fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-60 hidden">
    <div class="bg-white rounded-[16px] p-6 w-full max-w-md shadow-2xl">
        <div class="text-center mb-6">
            <div class="text-5xl mb-3">⚠️</div>
            <h3 class="text-xl font-bold text-red-600 mb-2">KONFIRMASI TERAKHIR!</h3>
            <p class="text-gray-700 text-sm">Anda yakin ingin menghapus <strong>SEMUA DATA</strong> dari database?</p>
            <p class="text-red-600 font-bold text-sm mt-2">Tindakan ini TIDAK DAPAT DIBATALKAN!</p>
        </div>
        <div class="flex gap-3">
            <button type="button" 
                    id="final-cancel" 
                    class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-100 transition">
                Batalkan
            </button>
            <button type="button" 
                    id="final-confirm" 
                    class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-white transition hover:opacity-90"
                    style="background-color: #e74c3c;">
                Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab-index]');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    // Get color values from component
    const selectedBg = '<?= colors("white_neutral") ?>';
    const selectedShadow = '0px 2px 5.5px 0px rgba(0,0,0,0.07)';
    
    // Check URL parameter first, then localStorage
    const urlParams = new URLSearchParams(window.location.search);
    const urlTab = urlParams.get('tab');
    let activeTabIndex = '0';
    
    // Map tab names to indices
    const tabMap = {
        'registration': '0',
        'highlight-programs': '1',
        'testimonials': '2',
        'events': '3',
        'faqs': '4',
        'users': '5',
        'email': '6'
    };
    
    if (urlTab && tabMap[urlTab] !== undefined) {
        activeTabIndex = tabMap[urlTab];
        // Update localStorage with URL parameter
        localStorage.setItem('activeSettingsTabIndex', activeTabIndex);
        // Clean URL (remove tab parameter)
        window.history.replaceState({}, '', window.location.pathname);
    } else {
        // Fallback to localStorage
        activeTabIndex = localStorage.getItem('activeSettingsTabIndex') || '0';
    }
    
    // Function to update tab button styles
    function updateTabButtons(activeIndex) {
        tabButtons.forEach(button => {
            if (button.dataset.tabIndex === activeIndex) {
                button.style.background = selectedBg;
                button.style.boxShadow = selectedShadow;
            } else {
                button.style.background = 'transparent';
                button.style.boxShadow = 'none';
            }
        });
    }
    
    // Function to show active tab panel
    function showActiveTab(index) {
        tabPanels.forEach(panel => {
            panel.classList.add('hidden');
        });
        const activePanel = document.getElementById('tab-panel-' + index);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }
    }
    
    // Initialize - show active tab
    showActiveTab(activeTabIndex);
    updateTabButtons(activeTabIndex);
    
    // Add click event listeners
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.dataset.tabIndex;
            
            // Store active tab in localStorage
            localStorage.setItem('activeSettingsTabIndex', index);
            
            // Update UI
            updateTabButtons(index);
            showActiveTab(index);
        });
    });
});
</script>

<!-- Reset Database Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnReset = document.getElementById('btn-reset-database');
    const modal = document.getElementById('modal-reset-database');
    const closeBtn = document.getElementById('close-reset-modal');
    const cancelBtn = document.getElementById('cancel-reset');
    const form = document.getElementById('form-reset-database');
    
    // Open modal
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close modal handlers
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        form.reset();
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeModal);
    }
    
    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Final confirmation modal handlers
    const finalConfirmModal = document.getElementById('modal-final-confirm');
    const finalCancelBtn = document.getElementById('final-cancel');
    const finalConfirmBtn = document.getElementById('final-confirm');
    
    if (finalCancelBtn) {
        finalCancelBtn.addEventListener('click', function() {
            finalConfirmModal.classList.add('hidden');
        });
    }
    
    if (finalConfirmBtn) {
        finalConfirmBtn.addEventListener('click', async function() {
            finalConfirmModal.classList.add('hidden');
            await submitResetRequest();
        });
    }
    
    // Function to submit reset request
    async function submitResetRequest() {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = '🔄 Mereset Database...';
        
        try {
            const response = await fetch('<?= url('/admin/settings/database/reset') ?>', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('✅ Database berhasil direset!', 'success', 3000);
                closeModal();
                setTimeout(() => location.reload(), 2000);
            } else {
                showToast(result.message || 'Gagal mereset database', 'error', 5000);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } catch (error) {
            showToast('Terjadi kesalahan: ' + error.message, 'error', 5000);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('reset-password').value;
            const confirmation = document.getElementById('reset-confirmation').value;
            
            // Validate confirmation text
            if (confirmation !== 'RESET DATABASE') {
                showToast('Ketik "RESET DATABASE" dengan benar untuk melanjutkan', 'error', 5000);
                return;
            }
            
            // Show final confirmation modal
            document.getElementById('modal-final-confirm').classList.remove('hidden');
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
