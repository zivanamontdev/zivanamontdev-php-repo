<?php
$pageTitle = 'Tentang Kami';

// Get data from controller
$kepalaSekolah = $kepalaSekolah ?? null;
$karyawan = $karyawan ?? [];
$fasilitas = $fasilitas ?? [];

// Helper function to get employee photo URL
if (!function_exists('getEmployeePhotoUrl')) {
    function getEmployeePhotoUrl($photoPath) {
        if (empty($photoPath)) {
            return null; // Return null for empty photos to show placeholder
        }
        // If path is already a full URL (http:// or https://), return as-is
        if (strpos($photoPath, 'http://') === 0 || strpos($photoPath, 'https://') === 0) {
            return $photoPath;
        }
        // If path already includes 'uploads/', use it directly
        if (strpos($photoPath, 'uploads/') === 0) {
            return url($photoPath);
        }
        return asset('uploads/' . $photoPath);
    }
}

// Helper function to get fasilitas cover image URL
if (!function_exists('getFasilitasCoverImage')) {
    function getFasilitasCoverImage($imagePath) {
        if (empty($imagePath)) {
            return null; // Return null for empty images to show placeholder
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

ob_start(); 
?>

<!-- Page Header -->
<?php component('page_hero', ['title' => 'Profil dan Informasi Tentang Kami', 'variant' => 'primary']); ?>

<!-- Section 1: Sejarah Singkat -->
<?php component('widget/profile/profile_section', ['prakata' => $prakata ?? []]); ?>

<!-- Section 2: Visi Misi -->
<?php component('widget/profile/profile_visimisi'); ?>

<!-- Section 3: Team --><section class="container mx-auto px-5 mt-[80px]" data-reveal data-initial="6" data-desktop-initial="12" data-step="6">
    <div class="flex justify-center">
        <?php component('badge', ['text' => 'Kenalan dengan Kami']); ?>
    </div>
    
    <div class="mt-[32px] grid grid-cols-1 md:grid-cols-4 gap-[16px]">
        <!-- Kepala Sekolah - 2x2 Grid (Desktop), Full Width (Mobile) -->
        <?php if ($kepalaSekolah): ?>
            <div class="md:col-span-2 md:row-span-2 h-[384px] md:h-[760px]">
                <?php component('widget/profile/profile_team', [
                    'teamImage' => getEmployeePhotoUrl($kepalaSekolah['photo'] ?? ''),
                    'teamName' => $kepalaSekolah['name'] ?? 'Belum ada data',
                    'teamRole' => 'Kepala Sekolah',
                    'teamLarge' => true
                ]); ?>
            </div>
        <?php endif; ?>
        
        <!-- Karyawan Cards -->
        <?php 
        // Display karyawan
        $displayedKaryawan = $karyawan; // Keep all staff available, preserving the existing order.
        
        foreach ($displayedKaryawan as $index => $k): 
        ?>
            <div class="karyawan-item h-[234px] md:h-[372px] <?= $index >= 6 && $index < 12 ? 'reveal-desktop-only' : '' ?>" data-reveal-item <?= $index >= 12 ? 'hidden' : '' ?> data-index="<?= $index ?>">
                <?php component('widget/profile/profile_team', [
                    'teamImage' => getEmployeePhotoUrl($k['photo'] ?? ''),
                    'teamName' => $k['name'] ?? 'Nama Anggota',
                    'teamRole' => $k['role'] ?? 'Guru'
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Tampilkan Lebih Banyak/Sedikit Button (Mobile Only) -->
    <?php if (count($displayedKaryawan) > 6): ?>
    <div class="flex justify-center mt-[32px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button', 'id' => 'btn-toggle-karyawan', 'class' => count($karyawan) <= 12 ? 'reveal-mobile-only' : '', 'attrs' => ['data-reveal-more' => '']]); ?>
    </div>
    <?php endif; ?>
    

</section>

<!-- Section 4: Fasilitas Sekolah -->
<section class="container mx-auto px-5 mt-[84px]" data-reveal data-initial="5" data-desktop-initial="all" data-step="3">
    <div class="flex justify-center">
        <?php component('badge', ['text' => 'Fasilitas Sekolah']); ?>
    </div>
    
    <div class="mt-[16px] grid grid-cols-1 md:grid-cols-2 gap-[24px]">
        <?php if (!empty($fasilitas)): ?>
            <?php foreach ($fasilitas as $index => $item): ?>
                <div class="fasilitas-item <?= $index >= 5 ? 'reveal-desktop-only' : '' ?>" data-reveal-item data-index="<?= $index ?>">
                    <?php component('widget/profile/profile_fasilitas', [
                        'fasilitasTitle' => $item['name'],
                        'fasilitasImage' => getFasilitasCoverImage($item['image']),
                        'fasilitasId' => $item['id']
                    ]); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State Placeholder -->
            <div class="bg-white-neutral rounded-[24px] overflow-hidden opacity-60">
                <!-- Image Placeholder -->
                <div class="w-full h-[240px] bg-gray-placeholder flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="p-[24px]">
                    <h3 class="font-bold text-[20px] md:text-[24px] leading-[32px] text-gray-400 mb-[8px]">
                        Belum Ada Data Fasilitas
                    </h3>
                    <p class="font-normal text-[16px] leading-[28px] text-gray-400">
                        Informasi fasilitas sekolah akan ditampilkan di sini setelah data ditambahkan melalui halaman admin.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Tampilkan Lebih Banyak/Sedikit Button (Mobile Only) -->
    <?php if (!empty($fasilitas) && count($fasilitas) > 5): ?>
    <div class="flex justify-center mt-[32px] md:hidden">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button', 'id' => 'btn-toggle-fasilitas', 'attrs' => ['data-reveal-more' => '']]); ?>
    </div>
    <?php endif; ?>
    

</section>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
