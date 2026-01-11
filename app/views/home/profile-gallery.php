<?php 
// Get data from controller
$fasilitas = $fasilitas ?? null;
$galleryImages = $galleryImages ?? [];
$allFasilitas = $allFasilitas ?? [];

// Helper function to get gallery image URL
if (!function_exists('getGalleryImageUrl')) {
    function getGalleryImageUrl($imagePath) {
        if (empty($imagePath)) {
            return asset('images/default-gallery.jpg');
        }
        // If path is already a full URL (http:// or https://), return as-is
        if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
            return $imagePath;
        }
        // If path already includes 'uploads/', use it directly
        if (strpos($imagePath, 'uploads/') === 0) {
            return url($imagePath);
        }
        return asset('uploads/' . $imagePath);
    }
}

$pageTitle = $fasilitas ? 'Galeri ' . $fasilitas['name'] : 'Galeri Fasilitas';
ob_start(); 
?>

<!-- Page Header -->
<div class="-mt-20 mb-[40px]">
    <?php 
    $heroTitle = $fasilitas ? $fasilitas['name'] : 'Galeri Fasilitas';
    component('page_hero', ['title' => $heroTitle, 'variant' => 'secondary']); 
    ?>
</div>

<!-- Back Button & Gallery Content -->
<section class="container mx-auto px-5">
    <!-- Back Button -->
    <div class="flex justify-center md:justify-start mb-[32px]">
        <?php component('button', ['text' => 'Kembali ke Profil Sekolah', 'variant' => '4', 'href' => url('/profile')]); ?>
    </div>
    
    <?php if (!empty($galleryImages)): ?>
    <!-- Gallery Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-[24px]" x-data="{ showModal: false, currentImage: '', currentTitle: '' }">
        <?php foreach ($galleryImages as $item): ?>
        <div 
            class="bg-white-neutral rounded-[20px] p-[16px] h-[264px] cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="showModal = true; currentImage = <?= htmlspecialchars(json_encode(getGalleryImageUrl($item['image_path'])), ENT_QUOTES, 'UTF-8') ?>; currentTitle = <?= htmlspecialchars(json_encode($item['description'] ?? ($fasilitas['name'] ?? 'Fasilitas')), ENT_QUOTES, 'UTF-8') ?>"
        >
            <!-- Image -->
            <div class="h-[184px] mb-[16px] rounded-[12px] overflow-hidden">
                <img 
                    src="<?= getGalleryImageUrl($item['image_path']) ?>" 
                    alt="<?= e($item['description'] ?? ($fasilitas['name'] ?? 'Fasilitas')) ?>" 
                    class="w-full h-full object-cover object-center"
                >
            </div>
            
            <!-- Title -->
            <h3 class="font-normal text-[14px] leading-[24px] md:text-[20px] md:leading-[140%] text-black-soft">
                <?= e($item['description'] ?? ($fasilitas['name'] ?? 'Fasilitas')) ?>
            </h3>
        </div>
        <?php endforeach; ?>
        
        <!-- Modal -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center"
            @click.self="showModal = false"
            @keydown.escape.window="showModal = false"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/70" @click="showModal = false"></div>
            
            <!-- Modal Content -->
            <div class="relative z-10 max-w-[1116px] w-full mx-4">
                <!-- Header: Preview Foto & Close Button -->
                <div class="flex justify-between items-center mb-[12px]">
                    <h3 class="font-bold text-[14px] leading-[24px] md:text-[20px] md:leading-[32px] text-white-neutral">Preview Foto</h3>
                    <button @click="showModal = false" class="text-white-neutral hover:opacity-80 transition-opacity">
                        <svg class="w-[24px] h-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Image -->
                <div class="w-full h-[158px] md:h-[626px] rounded-[12px] overflow-hidden mb-[36px]">
                    <img 
                        :src="currentImage" 
                        alt="" 
                        class="w-full h-full object-cover"
                    >
                </div>
                
                <!-- Title -->
                <p class="font-normal text-[16px] leading-[28px] md:text-[24px] md:leading-[32px] text-white-neutral text-center" x-text="currentTitle"></p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">
            Belum ada foto galeri
        </h3>
        <p class="text-gray-500">
            Galeri untuk fasilitas ini belum tersedia
        </p>
    </div>
    <?php endif; ?>
    
    <!-- Tampilkan Lebih Banyak Button - Hidden for now -->
    <?php if (false && !empty($galleryImages)): ?>
    <div class="flex justify-center mt-[32px] mb-[88px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'href' => '#']); ?>
    </div>
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
