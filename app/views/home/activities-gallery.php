<?php 
$pageTitle = 'Galeri Aktivitas';
ob_start(); 
?>

<!-- Page Header -->
<?php 
$heroTitle = !empty($programData) ? 'Galeri ' . $programData['name'] : 'Galeri Aktivitas Sensori';
component('page_hero', ['title' => $heroTitle, 'variant' => 'secondary']); 
?>

<!-- Back Button -->
<section class="container mx-auto px-5 mt-[32px]">
    <div class="flex justify-center md:justify-start mb-[32px]">
        <?php component('button', ['text' => 'Kembali ke Aktivitas Sekolah', 'variant' => '4', 'href' => url('/activities'), 'customPadding' => 'py-2 px-4']); ?>
    </div>
</section>

<!-- Gallery Content -->
<section class="container mx-auto px-5">
    
    <!-- Gallery Cards -->
    <?php if (!empty($galleryData)): ?>
        <?php component('widget/activities/activities_gallery_card', ['items' => $galleryData]); ?>
    <?php else: ?>
        <div class="text-center py-[40px]">
            <p class="text-black-soft text-[20px]">Belum ada galeri untuk program ini.</p>
        </div>
    <?php endif; ?>
    
    <!-- Tampilkan Lebih Banyak Button -->
    <?php if (!empty($galleryData) && count($galleryData) > 6): ?>
    <div class="flex justify-center mt-[32px] mb-[88px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button']); ?>
    </div>
    <?php else: ?>
    <div class="mb-[88px]"></div>
    <?php endif; ?>
</section>

<!-- Floating Vector Galeri -->
<div class="relative">
    <img 
        src="<?= asset('images/vectors/vector_galeri.png') ?>" 
        alt="" 
        class="hidden md:block absolute right-[50px] -top-[260px] w-[220px] h-[190px] z-10 pointer-events-none"
    >
</div>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
