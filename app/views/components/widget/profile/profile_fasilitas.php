<?php
/**
 * Profile Fasilitas Widget Component
 * 
 * Card untuk menampilkan fasilitas sekolah
 * 
 * @param string $fasilitasTitle - Judul fasilitas
 * @param string $fasilitasImage - Gambar fasilitas
 */

$fasilitasTitle = $fasilitasTitle ?? 'Nama Fasilitas';
$fasilitasImage = $fasilitasImage ?? 'image_fasilitas_1.png';
?>

<div class="bg-white-neutral rounded-[20px] px-[24px] py-[16px] h-[315px] flex flex-col">
    <!-- Header: Title & Link -->
    <div class="flex justify-between items-center">
        <h4 class="font-bold text-[24px] leading-[100%] text-black-soft">
            <?= e($fasilitasTitle) ?>
        </h4>
        <a href="<?= url('/profile-gallery') ?>" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
            <span class="font-normal text-[20px] leading-[100%] text-primary">Lihat Galeri</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="#C92C2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
    
    <!-- Image -->
    <div class="mt-[20px] flex-1 rounded-[12px] overflow-hidden">
        <img 
            src="<?= url('images/' . $fasilitasImage) ?>" 
            alt="<?= e($fasilitasTitle) ?>" 
            class="w-full h-full object-cover object-center"
        >
    </div>
</div>
