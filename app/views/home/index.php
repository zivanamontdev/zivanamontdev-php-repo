<?php 
$pageTitle = 'Home';
ob_start(); 

// Data keunggulan sekolah
$keunggulanItems = [
    [
        'image' => 'card1_telescope.png',
        'title' => 'Guru Bersertifikat Montessori',
        'description' => 'Pembelajaran dengan pendekatan personal sesuai karakter anak.'
    ],
    [
        'image' => 'card2_car.png',
        'title' => 'Kegiatan Interaktif & Kreatif',
        'description' => 'Setiap hari anak belajar lewat pengalaman langsung.'
    ],
    [
        'image' => 'card3_hourse.png',
        'title' => 'Fasilitas Aman & Nyaman',
        'description' => 'Ruang belajar bersih, area bermain luas, dan lingkungan positif.'
    ],
    [
        'image' => 'card4_love.png',
        'title' => 'Pendekatan Karakter & Empati',
        'description' => 'Fokus pada pembentukan karakter sejak dini.'
    ],
];
?>

<!-- Hero Section -->
<div class="mt-[52px]">
    <?php component('widget/home_hero'); ?>
</div>

<!-- Keunggulan Sekolah Section -->
<section class="mt-[120px]">
    <div class="container mx-auto">
        <?php component('badge', ['text' => 'Keunggulan Sekolah']); ?>
        
        <!-- Cards Grid -->
        <div class="relative">
            <!-- Vector decoration -->
            <img 
                src="<?= url('/images/vectors/vector_keunggulan.png') ?>" 
                alt="" 
                class="absolute -top-[76px] -right-4 w-[68px] h-[74px] -rotate-6 pointer-events-none z-10"
            >
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                <?php foreach ($keunggulanItems as $item): ?>
                    <?php component('widget/card_keunggulan', [
                        'image' => $item['image'],
                        'title' => $item['title'],
                        'description' => $item['description']
                    ]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="mt-[120px] relative">
    <!-- Vector About (floating between sections) -->
    <img 
        src="<?= url('/images/vectors/vector_about.png') ?>" 
        alt="" 
        class="absolute -top-[120px] left-1/2 -translate-x-1/2 w-[143px] h-[143px] pointer-events-none z-10"
    >
    
    <div class="container mx-auto">
        <?php component('widget/home_about'); ?>
    </div>
</section>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
