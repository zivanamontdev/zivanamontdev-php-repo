<?php 
$pageTitle = 'Tentang Kami';
ob_start(); 
?>

<!-- Page Header -->
<section class="container mx-auto mt-[52px] mb-[80px]">
    <div class="bg-primary rounded-[32px] h-[132px] p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[40px] leading-[100%] text-white-neutral text-center">
            Profil dan Informasi Tentang Kami
        </h1>
    </div>
</section>

<!-- Section 1: Sejarah Singkat -->
<?php component('widget/profile/profile_section'); ?>

<!-- Section 2: Visi Misi -->
<?php component('widget/profile/profile_visimisi'); ?>

<!-- Section 3: Team -->
<section class="container mx-auto mt-[80px]">
    <?php component('badge', ['text' => 'Kenalan dengan Kami']); ?>
    
    <div class="mt-[32px] grid grid-cols-4 gap-[24px]">
        <!-- Row 1-2, Col 1-2: Large Card (2x2) -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Adilah Wina Fitria',
            'teamRole' => 'Kepala Sekolah',
            'teamLarge' => true
        ]); ?>
        
        <!-- Row 1, Col 3 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 1, Col 4 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 2, Col 3 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 2, Col 4 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 3, Col 1 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 3, Col 2 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 3, Col 3 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 3, Col 4 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 4, Col 1 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 4, Col 2 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 4, Col 3 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
        
        <!-- Row 4, Col 4 -->
        <?php component('widget/profile/profile_team', [
            'teamImage' => 'image_team.png',
            'teamName' => 'Nama Anggota',
            'teamRole' => 'Guru'
        ]); ?>
    </div>
</section>

<!-- Section 4: Fasilitas Sekolah -->
<section class="container mx-auto mt-[84px]">
    <?php component('badge', ['text' => 'Fasilitas Sekolah']); ?>
    
    <div class="mt-[16px] grid grid-cols-2 gap-[24px]">
        <?php component('widget/profile/profile_fasilitas', [
            'fasilitasTitle' => 'Ruang Kelas Montessori',
            'fasilitasImage' => 'image_fasilitas_1.png'
        ]); ?>
        
        <?php component('widget/profile/profile_fasilitas', [
            'fasilitasTitle' => 'Area Bermain Outdoor',
            'fasilitasImage' => 'image_fasilitas_2.png'
        ]); ?>
        
        <?php component('widget/profile/profile_fasilitas', [
            'fasilitasTitle' => 'Perpustakaan Mini',
            'fasilitasImage' => 'image_fasilitas_3.png'
        ]); ?>
        
        <?php component('widget/profile/profile_fasilitas', [
            'fasilitasTitle' => 'Ruang Seni & Kreativitas',
            'fasilitasImage' => 'image_fasilitas_4.png'
        ]); ?>
        
        <?php component('widget/profile/profile_fasilitas', [
            'fasilitasTitle' => 'Musholla',
            'fasilitasImage' => 'image_fasilitas_5.png'
        ]); ?>
    </div>
</section>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
