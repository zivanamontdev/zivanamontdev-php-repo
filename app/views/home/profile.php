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
<div class="mb-[80px]">
    <?php component('page_hero', ['title' => 'Profil dan Informasi Tentang Kami', 'variant' => 'primary']); ?>
</div>

<!-- Section 1: Sejarah Singkat -->
<?php component('widget/profile/profile_section'); ?>

<!-- Section 2: Visi Misi -->
<?php component('widget/profile/profile_visimisi'); ?>

<!-- Section 3: Team -->
<section class="container mx-auto px-5 mt-[80px]">
    <?php component('badge', ['text' => 'Kenalan dengan Kami']); ?>
    
    <div class="mt-[32px] grid grid-cols-4 gap-[24px]">
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
        
        foreach ($displayedKaryawan as $k): 
        ?>
            <?php component('widget/profile/profile_team', [
                'teamImage' => getEmployeePhotoUrl($k['photo'] ?? ''),
                'teamName' => $k['name'] ?? 'Nama Anggota',
                'teamRole' => $k['role'] ?? 'Guru'
            ]); ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section 4: Fasilitas Sekolah -->
<section class="container mx-auto px-5 mt-[84px]">
    <?php component('badge', ['text' => 'Fasilitas Sekolah']); ?>
    
    <div class="mt-[16px] grid grid-cols-2 gap-[24px]">
        <?php if (!empty($fasilitas)): ?>
            <?php foreach ($fasilitas as $item): ?>
                <?php component('widget/profile/profile_fasilitas', [
                    'fasilitasTitle' => $item['name'],
                    'fasilitasImage' => getFasilitasCoverImage($item['image']),
                    'fasilitasId' => $item['id']
                ]); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 text-center text-gray-500 py-8">
                Belum ada data fasilitas
            </div>
        <?php endif; ?>
    </div>
</section>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
