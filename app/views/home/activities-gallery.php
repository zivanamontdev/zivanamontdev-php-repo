<?php 
$pageTitle = 'Galeri Aktivitas';
ob_start(); 
?>

<!-- Page Header -->
<section class="container mx-auto mt-[52px] mb-[40px]">
    <div class="bg-secondary rounded-[32px] h-[132px] p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[40px] leading-[100%] text-black-soft text-center">
            Galeri Aktivitas Sensori
        </h1>
    </div>
</section>

<!-- Back Button & Gallery Content -->
<section class="container mx-auto">
    <!-- Back Button -->
    <div class="mb-[32px]">
        <?php component('button', ['text' => 'Kembali ke Aktifitas Sekolah', 'variant' => '4', 'href' => url('/activities')]); ?>
    </div>
    
    <!-- Gallery Cards -->
    <?php 
    $galleryData = [
        [
            'image' => 'activities_images_1.png',
            'description' => 'Anak-anak bermain dengan berbagai tekstur dan bahan sensorik untuk mengembangkan kemampuan motorik halus mereka.'
        ],
        [
            'image' => 'activities_images_2.png',
            'description' => 'Kegiatan eksplorasi warna dan bentuk menggunakan cat air dan playdough.'
        ],
        [
            'image' => 'activities_images_3.png',
            'description' => 'Bermain pasir kinetik untuk melatih koordinasi tangan dan kreativitas anak.'
        ],
        [
            'image' => 'activities_images_4.png',
            'description' => 'Aktivitas sensori dengan air dan berbagai alat untuk melatih konsentrasi dan fokus anak-anak dalam pembelajaran yang menyenangkan.'
        ],
        [
            'image' => 'activities_images_5.png',
            'description' => 'Kegiatan menyusun balok dan puzzle untuk mengembangkan kemampuan problem solving.'
        ]
    ];
    component('widget/activities/activities_gallery_card', ['items' => $galleryData]); 
    ?>
    
    <!-- Tampilkan Lebih Banyak Button -->
    <div class="flex justify-center mt-[32px] mb-[88px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'href' => '#']); ?>
    </div>
</section>

<!-- Floating Vector Galeri -->
<div class="relative">
    <img 
        src="<?= url('images/vectors/vector_galeri.png') ?>" 
        alt="" 
        class="absolute right-[50px] -top-[260px] w-[220px] h-[190px] z-10 pointer-events-none"
    >
</div>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
