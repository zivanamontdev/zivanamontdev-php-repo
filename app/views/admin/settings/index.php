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

<!-- Tabs -->
<div style="margin-bottom: 16px;">
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

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
