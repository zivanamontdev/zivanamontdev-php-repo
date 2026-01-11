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
    ['label' => 'Aktivitas', 'url' => url('/activities')],
    ['label' => 'Artikel', 'url' => url('/articles')],
    ['label' => 'Tentang Kami', 'url' => url('/profile')],
];

// Get current URL path for active state
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if current path starts with /activities (for sub-pages like /activities-gallery)
function isActivitiesPage($path) {
    return $path === '/activities' || strpos($path, '/activities-') === 0;
}

// Check if current path is article related (for sub-pages like /article/slug)
function isArticlesPage($path) {
    return $path === '/articles' || strpos($path, '/article/') === 0 || strpos($path, '/article-') === 0;
}
?>

<!-- Navigation -->
<nav class="bg-white-neutral fixed w-full top-0 z-50">
    <div class="lg:container mx-auto px-5">
        <div class="flex justify-between items-center py-4">
            <!-- Mobile Left Section: Menu Icon + Logo -->
            <div class="flex md:hidden items-center gap-3">
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="text-black-highlight hover:text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo Mobile -->
                <a href="<?= url('/') ?>" class="flex-shrink-0" id="mobile-logo">
                    <img src="<?= asset('images/logo.png') ?>" alt="<?= e($settings['school_name'] ?? APP_NAME) ?>" width="65" height="24" class="h-[24px] w-[65px] object-contain">
                </a>
            </div>
            
            <!-- Desktop Logo -->
            <a href="<?= url('/') ?>" class="hidden md:block flex-shrink-0">
                <img src="<?= asset('images/logo.png') ?>" alt="<?= e($settings['school_name'] ?? APP_NAME) ?>" width="164" height="60" class="h-[60px] w-[164px] object-contain">
            </a>
            
            <div class="hidden md:flex items-center justify-center flex-1 px-8">
                <div class="flex items-center gap-[16px]">
                    <?php foreach ($menuItems as $item): 
                        $itemPath = parse_url($item['url'], PHP_URL_PATH);
                        // Special handling for Aktifitas menu - also active on sub-pages
                        if ($itemPath === '/activities') {
                            $isActive = isActivitiesPage($currentPath);
                        } elseif ($itemPath === '/articles') {
                            $isActive = isArticlesPage($currentPath);
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
            
            <!-- Register Button Mobile - Using Variant 2 -->
            <div class="md:hidden flex-shrink-0">
                <?php component('button', [
                    'text' => 'Daftar',
                    'variant' => '2',
                    'href' => url('/registration')
                ]); ?>
            </div>
            
            <!-- Register Button Desktop - Using Variant 2 -->
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
                    } elseif ($itemPath === '/articles') {
                        $isActive = isArticlesPage($currentPath);
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
    
    // Close mobile menu when clicking on menu items
    document.querySelectorAll('#mobile-menu a').forEach(function(link) {
        link.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
    
    // Close mobile menu when clicking on mobile logo
    document.getElementById('mobile-logo').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.add('hidden');
    });
</script>
