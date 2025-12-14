<?php
/**
 * Fasilitas Card Component
 * Card untuk menampilkan fasilitas dengan gallery count
 * Mirip dengan program tahun ajaran card
 * 
 * @param int $id - Fasilitas ID
 * @param string $name - Nama fasilitas
 * @param int $galleryCount - Jumlah gambar gallery
 */

$id = $id ?? '';
$name = $name ?? '';
$galleryCount = $galleryCount ?? 0;
?>

<div class="bg-white-neutral border border-border-light rounded-[16px] px-[16px] py-[12px]">
    <!-- Header with Title and Edit Button -->
    <div class="flex items-center mb-[12px]">
        <div class="flex-1">
            <h4 class="font-bold text-[12px] leading-[100%] text-text-dark"><?= htmlspecialchars($name) ?></h4>
        </div>
        <div class="flex-shrink-0">
            <?php component('button', [
                'variant' => '9',
                'icon' => 'edit',
                'type' => 'button',
                'id' => 'btn-edit-fasilitas-' . $id,
                'class' => 'btn-edit-fasilitas',
                'attrs' => [
                    'data-fasilitas-id' => $id,
                    'data-fasilitas-name' => $name
                ]
            ]); ?>
        </div>
    </div>
    
    <!-- Galeri Label -->
    <p class="font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">Galeri Fasilitas</p>
    
    <!-- Gallery Preview -->
    <?php if ($galleryCount > 0): ?>
        <div class="flex items-center gap-2 mb-[12px]">
            <div class="flex -space-x-2">
                <!-- Show first 3 images placeholder -->
                <?php for ($i = 0; $i < min(3, $galleryCount); $i++): ?>
                <div class="w-[40px] h-[40px] rounded-lg bg-gray-placeholder border-2 border-white-neutral flex items-center justify-center">
                    <svg class="w-4 h-4 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <?php endfor; ?>
                <?php if ($galleryCount > 3): ?>
                <div class="w-[40px] h-[40px] rounded-lg bg-primary border-2 border-white-neutral flex items-center justify-center">
                    <span class="text-[10px] font-bold text-white-neutral">+<?= $galleryCount - 3 ?></span>
                </div>
                <?php endif; ?>
            </div>
            <span class="text-[10px] font-normal text-black-highlight"><?= $galleryCount ?> Foto</span>
        </div>
    <?php else: ?>
        <p class="text-[10px] font-normal text-white-shadow mb-[12px]">Belum ada foto</p>
    <?php endif; ?>
    
    <!-- Gallery Button -->
    <?php component('button', [
        'text' => 'Kelola Galeri Fasilitas',
        'variant' => '14',
        'type' => 'button',
        'id' => 'btn-gallery-fasilitas-' . $id,
        'class' => 'btn-gallery-fasilitas w-full',
        'attrs' => [
            'data-fasilitas-id' => $id,
            'data-fasilitas-name' => $name
        ]
    ]); ?>
</div>
