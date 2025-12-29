<?php
/**
 * Profile Fasilitas Widget Component
 * 
 * Card untuk menampilkan fasilitas sekolah
 * 
 * @param string $fasilitasTitle - Judul fasilitas
 * @param string|null $fasilitasImage - Gambar fasilitas (full URL or path)
 * @param int|null $fasilitasId - ID fasilitas untuk link ke gallery
 */

$fasilitasTitle = $fasilitasTitle ?? 'Nama Fasilitas';
$fasilitasImage = $fasilitasImage ?? '';
$fasilitasId = $fasilitasId ?? null;

// Check if image should show placeholder
$showPlaceholder = empty($fasilitasImage);

// Generate gallery link
$galleryUrl = $fasilitasId 
    ? url('/profile-gallery?id=' . $fasilitasId)
    : url('/profile-gallery');
?>

<div class="bg-white-neutral rounded-[20px] px-[24px] py-[16px] h-[315px] flex flex-col">
    <!-- Header: Title & Link -->
    <!-- Desktop: Title dan Link bersebelahan -->
    <div class="hidden md:flex justify-between items-center">
        <h4 class="font-bold text-[24px] leading-[100%] text-black-soft">
            <?= e($fasilitasTitle) ?>
        </h4>
        <a href="<?= $galleryUrl ?>" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
            <span class="font-normal text-[20px] leading-[100%] text-primary">Lihat Galeri</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="#C92C2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
    
    <!-- Mobile: Title dan Link vertical -->
    <div class="md:hidden">
        <h4 class="font-bold text-[20px] leading-[32px] text-black-soft mb-[12px]">
            <?= e($fasilitasTitle) ?>
        </h4>
        <a href="<?= $galleryUrl ?>" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
            <span class="font-normal text-[20px] leading-[100%] text-primary">Lihat Galeri</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="#C92C2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
    
    <!-- Image -->
    <div class="mt-[20px] flex-1 rounded-[12px] overflow-hidden <?= $showPlaceholder ? 'bg-gray-100 flex items-center justify-center' : '' ?>">
        <?php if ($showPlaceholder): ?>
            <!-- Placeholder Icon -->
            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        <?php else: ?>
            <img 
                src="<?= $fasilitasImage ?>" 
                alt="<?= e($fasilitasTitle) ?>" 
                class="w-full h-full object-cover object-center"
                onerror="this.style.display='none'; this.parentElement.classList.add('bg-gray-100', 'flex', 'items-center', 'justify-center'); this.parentElement.innerHTML='<svg class=&quot;w-24 h-24 text-gray-400&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;1.5&quot; d=&quot;M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4&quot;></path></svg>';"
            >
        <?php endif; ?>
    </div>
</div>
