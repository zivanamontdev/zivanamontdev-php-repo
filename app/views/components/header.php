<?php
/**
 * Header/Navigation Component
 * 
 * @param array $settings - Site settings
 * @param array $socialMedia - Social media links (optional)
 */

$settings = $settings ?? [];
$socialMedia = $socialMedia ?? [];

// Navigation menu items
$menuItems = [
    ['label' => 'Aktifitas', 'url' => url('/activities')],
    ['label' => 'Artikel', 'url' => url('/articles')],
    ['label' => 'Tentang Kami', 'url' => url('/profile')],
];

// Get current URL path for active state
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if current path starts with /activities (for sub-pages like /activities-gallery)
function isActivitiesPage($path) {
    return $path === '/activities' || strpos($path, '/activities-') === 0;
}
?>

<!-- Navigation -->
<nav class="bg-white-neutral fixed w-full top-0 z-50">
    <div class="container mx-auto">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="<?= url('/') ?>" class="flex-shrink-0">
                <img src="<?= url('/images/logo.png') ?>" alt="<?= e($settings['school_name'] ?? APP_NAME) ?>" width="164" height="60" class="h-[60px] w-[164px] object-contain">
            </a>
            
            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="md:hidden text-black-highlight hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <div class="hidden md:flex items-center justify-center flex-1 px-8">
                <div class="flex items-center gap-[16px]">
                    <?php foreach ($menuItems as $item): 
                        $itemPath = parse_url($item['url'], PHP_URL_PATH);
                        // Special handling for Aktifitas menu - also active on sub-pages
                        if ($itemPath === '/activities') {
                            $isActive = isActivitiesPage($currentPath);
                        } else {
                            $isActive = $currentPath === $itemPath;
                        }
                    ?>
                        <a href="<?= $item['url'] ?>" class="w-[136px] text-center <?= $isActive ? 'text-primary font-semibold' : 'text-black-highlight hover:text-primary' ?> font-medium transition-colors duration-200">
                            <?= e($item['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Register Button - Using Variant 2 -->
            <div class="hidden md:block flex-shrink-0">
                <?php component('button', [
                    'text' => 'Daftar ke Sekolah',
                    'variant' => '2',
                    'href' => url('/registration')
                ]); ?>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-3">
                <?php foreach ($menuItems as $item): 
                    $itemPath = parse_url($item['url'], PHP_URL_PATH);
                    // Special handling for Aktifitas menu - also active on sub-pages
                    if ($itemPath === '/activities') {
                        $isActive = isActivitiesPage($currentPath);
                    } else {
                        $isActive = $currentPath === $itemPath;
                    }
                ?>
                    <a href="<?= $item['url'] ?>" class="<?= $isActive ? 'text-primary font-semibold' : 'text-black-highlight hover:text-primary' ?> font-medium py-2 transition-colors duration-200">
                        <?= e($item['label']) ?>
                    </a>
                <?php endforeach; ?>
                
                <!-- Register Button Mobile - Using Variant 2 -->
                <div class="pt-2">
                    <?php component('button', [
                        'text' => 'Daftar ke Sekolah',
                        'variant' => '2',
                        'href' => url('/registration'),
                        'class' => 'w-full text-center'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
