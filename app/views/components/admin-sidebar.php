<?php
/**
 * Admin Sidebar Component
 * 
 * @param string $currentPage - Current active page identifier
 */

$currentPage = $currentPage ?? 'dashboard';

// Menu items configuration
$menuItems = [
    [
        'id' => 'dashboard',
        'label' => 'Dashboard',
        'icon' => 'grid',
        'href' => url('/admin/dashboard'),
    ],
    [
        'id' => 'registrations',
        'label' => 'Riwayat Pendaftar',
        'icon' => 'list',
        'href' => url('/admin/registrations'),
    ],
    [
        'id' => 'activities',
        'label' => 'Aktivitas Sekolah',
        'icon' => 'activity',
        'href' => url('/admin/activities'),
    ],
    [
        'id' => 'articles',
        'label' => 'Artikel/Berita',
        'icon' => 'bookmark',
        'href' => url('/admin/articles'),
    ],
    [
        'id' => 'management',
        'label' => 'Manajemen Sekolah',
        'icon' => 'tool',
        'href' => url('/admin/management'),
    ],
    [
        'id' => 'settings',
        'label' => 'Pengaturan',
        'icon' => 'setting',
        'href' => url('/admin/settings'),
    ],
];

// Icon SVGs (outline style, 16x16)
function getSidebarIcon($icon, $isActive = false) {
    $color = $isActive ? 'currentColor' : 'currentColor';
    
    $icons = [
        'grid' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="1" y="1" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
            <rect x="9" y="1" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
            <rect x="9" y="9" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
        </svg>',
        
        'list' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 4H14M5 8H14M5 12H14M2 4H2.01M2 8H2.01M2 12H2.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'activity' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 8H11.5L9.5 14L6.5 2L4.5 8H2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'bookmark' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 14V3C3 2.44772 3.44772 2 4 2H12C12.5523 2 13 2.44772 13 3V14L8 11L3 14Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'tool' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.5 6.5L2.5 10.5C2 11 2 12 2.5 12.5L3.5 13.5C4 14 5 14 5.5 13.5L9.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9.5 9.5L11 11M11 11L13.5 13.5M11 11L13.5 8.5M11 11L8.5 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 6C10 4.34315 11.3431 3 13 3C13.3506 3 13.6872 3.05891 14 3.16853V3C14 2.44772 13.5523 2 13 2H11C10.4477 2 10 2.44772 10 3V6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'setting' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M13.5 8C13.5 7.66 13.47 7.32 13.42 7L14.92 5.83C15.06 5.72 15.1 5.53 15.01 5.36L13.61 2.94C13.52 2.78 13.33 2.71 13.15 2.78L11.39 3.5C10.97 3.18 10.5 2.92 10 2.73L9.73 0.88C9.7 0.69 9.53 0.55 9.33 0.55H6.53C6.33 0.55 6.17 0.69 6.14 0.88L5.87 2.73C5.37 2.92 4.9 3.18 4.47 3.5L2.71 2.78C2.54 2.71 2.35 2.78 2.25 2.94L0.85 5.36C0.76 5.53 0.8 5.72 0.94 5.83L2.44 7C2.39 7.32 2.36 7.66 2.36 8C2.36 8.34 2.39 8.68 2.44 9L0.94 10.17C0.8 10.28 0.76 10.47 0.85 10.64L2.25 13.06C2.35 13.22 2.54 13.29 2.71 13.22L4.47 12.5C4.9 12.82 5.37 13.08 5.87 13.27L6.14 15.12C6.17 15.31 6.33 15.45 6.53 15.45H9.33C9.53 15.45 9.7 15.31 9.73 15.12L10 13.27C10.5 13.08 10.97 12.82 11.39 12.5L13.15 13.22C13.33 13.29 13.52 13.22 13.61 13.06L15.01 10.64C15.1 10.47 15.06 10.28 14.92 10.17L13.42 9C13.47 8.68 13.5 8.34 13.5 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'sidebar-toggle' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <path d="M7 3V17" stroke="currentColor" stroke-width="1.5"/>
        </svg>',
        
        'close' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'maximize' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 2H14V7M14 2L8 8M7 14H2V9M2 14L8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'forward' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'external' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 14H3C2.44772 14 2 13.5523 2 13V3C2 2.44772 2.44772 2 3 2H6M11 11L14 8M14 8L11 5M14 8H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
        
        'logout' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 14H13C13.5523 14 14 13.5523 14 13V3C14 2.44772 13.5523 2 13 2H10M6 11L3 8M3 8L6 5M3 8H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>',
    ];
    
    return $icons[$icon] ?? '';
}
?>

<!-- Admin Sidebar Wrapper -->
<div id="sidebar-wrapper" class="relative h-screen flex-shrink-0 w-[261px] transition-all duration-300">
    <!-- Floating Forward Button (shown when minimized) - vertically centered, outside sidebar -->
    <button id="sidebar-expand" 
            class="hidden fixed top-1/2 -translate-y-1/2 w-8 h-8 bg-white-neutral rounded-full shadow-lg 
                   items-center justify-center text-black-highlight hover:text-black-soft 
                   hover:bg-white-secondary transition-all duration-200 cursor-pointer z-50 border border-gray-200"
            style="left: 74px;">
        <?= getSidebarIcon('forward') ?>
    </button>
    
    <!-- Admin Sidebar -->
    <aside id="admin-sidebar" class="w-[261px] h-screen bg-white-neutral p-6 flex flex-col transition-all duration-300 overflow-hidden">
        <!-- Logo and Toggle -->
        <div id="logo-section" class="flex items-center justify-between mb-10 transition-all duration-300">
            <!-- Logo (Full) -->
            <img id="logo-full" src="<?= url('images/logo.png') ?>" alt="Logo" class="w-[133px] h-[46px] object-contain transition-all duration-300">
            
            <!-- Logo (Mini - shown when minimized) - 46x46px square -->
            <img id="logo-mini" src="<?= url('images/activity_logo.png') ?>" alt="Logo" class="w-[46px] h-[46px] object-contain hidden transition-all duration-300">
            
            <!-- Sidebar Toggle Icon (Expanded mode) -->
            <button id="sidebar-toggle" class="w-5 h-5 text-black-highlight hover:text-black-soft transition-all duration-300 cursor-pointer flex-shrink-0">
                <?= getSidebarIcon('sidebar-toggle') ?>
            </button>
        </div>
    
    <!-- Menu Items -->
    <nav class="flex flex-col gap-[18px]">
        <?php foreach ($menuItems as $item): 
            $isActive = $currentPage === $item['id'];
        ?>
            <a href="<?= $item['href'] ?>" 
               title="<?= e($item['label']) ?>"
               class="menu-item flex items-center gap-3 px-4 py-[13px] rounded-xl transition-all duration-300
                      <?php if ($isActive): ?>
                          bg-primary text-white-neutral font-bold
                      <?php else: ?>
                          text-white-shadow font-normal hover:bg-white-secondary
                      <?php endif; ?>">
                <!-- Icon -->
                <span class="w-4 h-4 flex-shrink-0 <?= $isActive ? 'text-white-neutral' : 'text-white-shadow' ?>">
                    <?= getSidebarIcon($item['icon'], $isActive) ?>
                </span>
                <!-- Label -->
                <span class="menu-label text-sm whitespace-nowrap transition-all duration-300"><?= e($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    
    <!-- Bottom Actions -->
    <div class="mt-auto pt-6">
        <a href="<?= url('/') ?>" target="_blank" 
           title="Lihat Website"
           class="menu-item flex items-center gap-3 px-4 py-[13px] rounded-xl text-white-shadow font-normal text-sm hover:bg-white-secondary transition-all duration-300">
            <span class="w-4 h-4 flex-shrink-0">
                <?= getSidebarIcon('external') ?>
            </span>
            <span class="menu-label whitespace-nowrap transition-all duration-300">Lihat Website</span>
        </a>
        
        <a href="<?= url('/admin/logout') ?>" 
           title="Logout"
           class="menu-item flex items-center gap-3 px-4 py-[13px] rounded-xl text-primary font-normal text-sm hover:bg-red-50 transition-all duration-300 mt-2">
            <span class="w-4 h-4 flex-shrink-0">
                <?= getSidebarIcon('logout') ?>
            </span>
            <span class="menu-label whitespace-nowrap transition-all duration-300">Logout</span>
        </a>
    </div>
    </aside>
</div>

<!-- Sidebar Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarWrapper = document.getElementById('sidebar-wrapper');
    const logoSection = document.getElementById('logo-section');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const expandBtn = document.getElementById('sidebar-expand');
    const logoFull = document.getElementById('logo-full');
    const logoMini = document.getElementById('logo-mini');
    const menuLabels = document.querySelectorAll('.menu-label');
    const menuItems = document.querySelectorAll('.menu-item');
    
    let isMinimized = false;
    
    function minimizeSidebar() {
        isMinimized = true;
        
        // Minimize sidebar and wrapper
        sidebar.classList.remove('w-[261px]');
        sidebar.classList.add('w-[90px]');
        sidebarWrapper.classList.remove('w-[261px]');
        sidebarWrapper.classList.add('w-[90px]');
        
        // Center logo section
        logoSection.classList.remove('justify-between');
        logoSection.classList.add('justify-center');
        
        // Switch logo
        logoFull.classList.add('hidden');
        logoMini.classList.remove('hidden');
        
        // Hide toggle button, show expand button
        toggleBtn.classList.add('hidden');
        expandBtn.classList.remove('hidden');
        expandBtn.classList.add('flex');
        expandBtn.style.left = '74px';
        
        // Hide menu labels with opacity transition
        menuLabels.forEach(label => {
            label.style.opacity = '0';
            label.style.width = '0';
            label.style.overflow = 'hidden';
        });
        
        // Center icons in menu items - remove gap, add justify-center
        menuItems.forEach(item => {
            item.classList.remove('gap-3');
            item.classList.add('justify-center');
        });
    }
    
    function expandSidebar() {
        isMinimized = false;
        
        // Expand sidebar and wrapper
        sidebar.classList.remove('w-[90px]');
        sidebar.classList.add('w-[261px]');
        sidebarWrapper.classList.remove('w-[90px]');
        sidebarWrapper.classList.add('w-[261px]');
        
        // Restore logo section
        logoSection.classList.add('justify-between');
        logoSection.classList.remove('justify-center');
        
        // Switch logo back
        logoFull.classList.remove('hidden');
        logoMini.classList.add('hidden');
        
        // Show toggle button, hide expand button
        toggleBtn.classList.remove('hidden');
        expandBtn.classList.add('hidden');
        expandBtn.classList.remove('flex');
        
        // Show menu labels
        menuLabels.forEach(label => {
            label.style.opacity = '1';
            label.style.width = 'auto';
            label.style.overflow = 'visible';
        });
        
        // Restore gap in menu items
        menuItems.forEach(item => {
            item.classList.add('gap-3');
            item.classList.remove('justify-center');
        });
    }
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', minimizeSidebar);
    }
    
    if (expandBtn && sidebar) {
        expandBtn.addEventListener('click', expandSidebar);
    }
});
</script>
