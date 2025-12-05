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
 */

$image = $image ?? '';
$title = $title ?? '';
$description = $description ?? '';

// Include card component untuk mendapatkan variant class
$variant = '2';
$class = '';
include VIEW_PATH . '/components/card.php';
?>

<div class="bg-white-neutral <?= $variantClass ?> <?= $class ?> flex flex-col h-full">
    <!-- Image -->
    <img 
        src="<?= url('/images/vectors/card_keunggulan/' . $image) ?>" 
        alt="<?= e($title) ?>" 
        class="w-full h-[224px] object-contain"
    >
    
    <!-- Title -->
    <h3 class="mt-[16px] font-bold text-[24px] leading-[38px] tracking-normal text-black-soft h-[76px] line-clamp-2">
        <?= e($title) ?>
    </h3>
    
    <!-- Description -->
    <p class="mt-[16px] font-normal text-[16px] leading-[28px] tracking-normal text-black-soft">
        <?= e($description) ?>
    </p>
</div>
