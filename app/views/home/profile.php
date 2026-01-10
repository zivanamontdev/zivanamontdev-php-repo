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
        return url('uploads/' . $photoPath);
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
        return url('uploads/' . $imagePath);
    }
}

ob_start(); 
?>

<!-- Page Header -->
<div class="mt-[40px] mb-[40px] md:mt-[52px] md:mb-[80px]">
    <?php component('page_hero', ['title' => 'Profil dan Informasi Tentang Kami', 'variant' => 'primary']); ?>
</div>

<!-- Section 1: Sejarah Singkat -->
<?php component('widget/profile/profile_section', ['prakata' => $prakata ?? []]); ?>

<!-- Section 2: Visi Misi -->
<?php component('widget/profile/profile_visimisi'); ?>

<!-- Section 3: Team -->
<section class="container mx-auto px-5 mt-[80px]">
    <div class="flex justify-center">
        <?php component('badge', ['text' => 'Kenalan dengan Kami']); ?>
    </div>
    
    <div class="mt-[32px] grid grid-cols-1 md:grid-cols-4 gap-[24px]">
        <!-- Row 1-2, Col 1-2: Large Card (2x2) - Kepala Sekolah -->
        <?php if ($kepalaSekolah): ?>
            <?php component('widget/profile/profile_team', [
                'teamImage' => getEmployeePhotoUrl($kepalaSekolah['photo'] ?? ''),
                'teamName' => $kepalaSekolah['name'] ?? 'Belum ada data',
                'teamRole' => 'Kepala Sekolah',
                'teamLarge' => true
            ]); ?>
        <?php endif; ?>
        
        <!-- Karyawan Cards -->
        <?php 
        // Limit to 11 karyawan (excluding kepala sekolah)
        // Grid layout: after large card (2x2), we have 2 slots in row 1, 2 in row 2, and 4 in row 3, 4 in row 4 = 12 slots total
        $maxKaryawan = 11;
        $displayedKaryawan = array_slice($karyawan, 0, $maxKaryawan);
        
        foreach ($displayedKaryawan as $index => $k): 
        ?>
            <div class="karyawan-item <?= $index >= 5 ? 'hidden md:block' : '' ?>" data-index="<?= $index ?>">
                <?php component('widget/profile/profile_team', [
                    'teamImage' => getEmployeePhotoUrl($k['photo'] ?? ''),
                    'teamName' => $k['name'] ?? 'Nama Anggota',
                    'teamRole' => $k['role'] ?? 'Guru'
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Tampilkan Lebih Banyak/Sedikit Button (Mobile Only) -->
    <?php if (count($displayedKaryawan) > 5): ?>
    <div class="flex justify-center mt-[32px] md:hidden">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button', 'id' => 'btn-toggle-karyawan']); ?>
    </div>
    <?php endif; ?>
    
    <script>
    const totalKaryawan = <?= count($displayedKaryawan) ?>;
    let currentVisibleKaryawan = 5;
    
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('btn-toggle-karyawan');
        const allItems = document.querySelectorAll('.karyawan-item');
        
        if (btnToggle) {
            btnToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (currentVisibleKaryawan < totalKaryawan) {
                    // Show more
                    const nextCount = Math.min(currentVisibleKaryawan + 3, totalKaryawan);
                    
                    // Show items from currentVisibleKaryawan to nextCount
                    for (let i = currentVisibleKaryawan; i < nextCount; i++) {
                        if (allItems[i]) {
                            allItems[i].classList.remove('hidden');
                        }
                    }
                    
                    currentVisibleKaryawan = nextCount;
                    
                    // Update button text if all shown
                    if (currentVisibleKaryawan >= totalKaryawan) {
                        btnToggle.textContent = 'Tampilkan Lebih Sedikit';
                    }
                } else {
                    // Show less - hide all except first 5 (kembali ke tampilan awal 6 data: 1 kepala sekolah + 5 karyawan)
                    for (let i = 5; i < allItems.length; i++) {
                        if (allItems[i]) {
                            allItems[i].classList.add('hidden');
                        }
                    }
                    
                    currentVisibleKaryawan = 5;
                    btnToggle.textContent = 'Tampilkan Lebih Banyak';
                }
            });
        }
    });
    </script>
</section>

<!-- Section 4: Fasilitas Sekolah -->
<section class="container mx-auto px-5 mt-[84px]">
    <div class="flex justify-center">
        <?php component('badge', ['text' => 'Fasilitas Sekolah']); ?>
    </div>
    
    <div class="mt-[16px] grid grid-cols-1 md:grid-cols-2 gap-[24px]">
        <?php if (!empty($fasilitas)): ?>
            <?php foreach ($fasilitas as $index => $item): ?>
                <div class="fasilitas-item <?= $index >= 5 ? 'hidden md:block' : '' ?>" data-index="<?= $index ?>">
                    <?php component('widget/profile/profile_fasilitas', [
                        'fasilitasTitle' => $item['name'],
                        'fasilitasImage' => getFasilitasCoverImage($item['image']),
                        'fasilitasId' => $item['id']
                    ]); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-1 md:col-span-2 text-center text-gray-500 py-8">
                Belum ada data fasilitas
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Tampilkan Lebih Banyak/Sedikit Button (Mobile Only) -->
    <?php if (!empty($fasilitas) && count($fasilitas) > 5): ?>
    <div class="flex justify-center mt-[32px] md:hidden">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button', 'id' => 'btn-toggle-fasilitas']); ?>
    </div>
    <?php endif; ?>
    
    <script>
    const totalFasilitas = <?= !empty($fasilitas) ? count($fasilitas) : 0 ?>;
    let currentVisibleFasilitas = 5;
    
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('btn-toggle-fasilitas');
        const allItems = document.querySelectorAll('.fasilitas-item');
        
        if (btnToggle) {
            btnToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (currentVisibleFasilitas < totalFasilitas) {
                    // Show more
                    const nextCount = Math.min(currentVisibleFasilitas + 3, totalFasilitas);
                    
                    // Show items from currentVisibleFasilitas to nextCount
                    for (let i = currentVisibleFasilitas; i < nextCount; i++) {
                        if (allItems[i]) {
                            allItems[i].classList.remove('hidden');
                        }
                    }
                    
                    currentVisibleFasilitas = nextCount;
                    
                    // Update button text if all shown
                    if (currentVisibleFasilitas >= totalFasilitas) {
                        btnToggle.textContent = 'Tampilkan Lebih Sedikit';
                    }
                } else {
                    // Show less - hide all except first 5
                    for (let i = 5; i < allItems.length; i++) {
                        if (allItems[i]) {
                            allItems[i].classList.add('hidden');
                        }
                    }
                    
                    currentVisibleFasilitas = 5;
                    btnToggle.textContent = 'Tampilkan Lebih Banyak';
                }
            });
        }
    });
    </script>
</section>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
