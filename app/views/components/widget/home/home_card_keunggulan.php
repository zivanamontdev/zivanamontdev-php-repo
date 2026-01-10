<?php
/**
 * Card Keunggulan Widget Component
 * 
 * Card untuk menampilkan keunggulan sekolah di halaman beranda
 * Menggunakan card component variant 2
 * 
 * @param string $image - Nama file gambar (e.g., 'card1_telescope.png')
 * @param string $title - Judul keunggulan
 * @param string $description - Deskripsi keunggulan
 * @param string $bgColor - Background color (optional, default: 'bg-white-neutral')
 */

$image = $image ?? '';
$title = $title ?? '';
$description = $description ?? '';
$bgColor = $bgColor ?? 'bg-white-neutral';

// Include card component untuk mendapatkan variant class
$variant = '2';
$class = '';
include VIEW_PATH . '/components/card.php';
?>

<div class="<?= $bgColor ?> <?= $variantClass ?> <?= $class ?> flex flex-col h-full">
    <!-- Image -->
    <?php 
    // Add cache busting parameter to force reload in production
    $imagePath = '/images/vectors/card_keunggulan/' . $image;
    $imageUrl = url($imagePath);
    
    // Add version parameter to bust cache (change this when updating images)
    $imageVersion = '20250110'; // Format: YYYYMMDD
    $imageUrl .= '?v=' . $imageVersion;
    ?>
    <img 
        src="<?= $imageUrl ?>" 
        alt="<?= e($title) ?>" 
        class="w-full h-[224px] object-contain"
    >
    
    <!-- Title -->
    <h3 class="mt-[16px] font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[32px] tracking-normal text-black-soft h-[76px] line-clamp-2 flex items-center">
        <?= e($title) ?>
    </h3>
    
    <!-- Description -->
    <p class="font-normal text-[14px] md:text-[16px] leading-[24px] md:leading-[28px] tracking-normal text-black-soft">
        <?= e($description) ?>
    </p>
</div>
