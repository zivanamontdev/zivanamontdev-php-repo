<?php
$pageTitle = 'Aktivitas Sekolah';
$currentPage = 'activities';
ob_start();
?>

<!-- Admin Navbar Component -->
<?php component('admin-navbar', ['title' => 'Aktivitas Sekolah']); ?>

<!-- Tabs -->
<?php component('tabs', [
    'tabs' => [
        'Informasi Kelas',
        'Program Tahun Ajaran',
        'Program Harian.'
    ],
    'active' => 0
]); ?>

<!-- Tab Content Panels -->
<div class="mt-4">
    <!-- Tab 1: Informasi Kelas -->
    <div id="tab-panel-0" class="tab-panel">
        <?php component('tab-content-card', [
            'title' => 'Informasi Kelas',
            'description' => 'Data kelas pada sekolah',
            'buttonText' => 'Tambah Kelas',
            'buttonHref' => '/admin/activities/classes/create',
            'buttonId' => 'btn-add-class'
        ]); ?>
    </div>
    
    <!-- Tab 2: Program Tahun Ajaran -->
    <div id="tab-panel-1" class="tab-panel hidden">
        <?php component('tab-content-card', [
            'title' => 'Program Tahun Ajaran',
            'description' => 'Daftar program tahun ajaran yang sedang berjalan',
            'buttonText' => 'Tambah Program',
            'buttonHref' => '/admin/activities/programs/create',
            'buttonId' => 'btn-add-program'
        ]); ?>
    </div>
    
    <!-- Tab 3: Program Harian -->
    <div id="tab-panel-2" class="tab-panel hidden">
        <?php component('tab-content-card', [
            'title' => 'Program Harian',
            'description' => 'Daftar program harian yang sedang berjalan',
            'buttonText' => '',
        ]); ?>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab-index]');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const index = this.dataset.tabIndex;
            
            // Hide all panels
            tabPanels.forEach(panel => panel.classList.add('hidden'));
            
            // Show selected panel
            const selectedPanel = document.getElementById('tab-panel-' + index);
            if (selectedPanel) {
                selectedPanel.classList.remove('hidden');
            }
        });
    });
});
</script>

<?php

$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
