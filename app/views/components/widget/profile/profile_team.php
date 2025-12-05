<?php
/**
 * Profile Team Widget Component
 * 
 * Grid 4x4 untuk menampilkan tim/anggota
 * Card dengan gambar background, nama dan role di bagian bawah
 * 
 * @param string $teamImage - Gambar anggota
 * @param string $teamName - Nama anggota
 * @param string $teamRole - Role/jabatan anggota
 * @param bool $teamLarge - Apakah card besar (2x2) atau kecil (1x1)
 */

$teamImage = $teamImage ?? 'image_team.png';
$teamName = $teamName ?? 'Nama Anggota';
$teamRole = $teamRole ?? 'Role';
$teamLarge = $teamLarge ?? false;

// Ukuran card
$heightClass = $teamLarge ? 'h-[768px]' : 'h-[372px]';
$colSpan = $teamLarge ? 'col-span-2 row-span-2' : '';
?>

<div class="<?= $colSpan ?> rounded-[12px] <?= $heightClass ?> relative overflow-hidden">
    <!-- Background Image with Grayscale -->
    <img 
        src="<?= url('images/' . $teamImage) ?>" 
        alt="<?= e($teamName) ?>" 
        class="absolute inset-0 w-full h-full object-cover object-center grayscale"
    >
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
    
    <!-- Content - Name & Role -->
    <div class="absolute bottom-0 left-0 right-0 p-[24px]">
        <h4 class="font-bold text-[24px] leading-[38px] text-white-neutral mb-[4px]">
            <?= e($teamName) ?>
        </h4>
        <p class="font-normal text-[20px] leading-[100%] text-white-neutral">
            <?= e($teamRole) ?>
        </p>
    </div>
</div>
