<?php
/**
 * Activities Kelas Widget Component
 * 
 * Displays class information cards
 * 
 * @param array $kelas - Array of class data with keys: image, title, usia, durasi, jumlah_murid
 */

$kelas = $kelas ?? [];
?>

<div class="relative">
    <!-- Floating Vector -->
    <img 
        src="<?= url('images/vectors/vector_highlight_kelas.png') ?>" 
        alt="" 
        class="absolute -left-[54px] -top-[57px] w-[68px] h-[74px] z-10 pointer-events-none"
    >
    
    <div class="grid grid-cols-3 gap-[24px]">
    <?php foreach ($kelas as $item): ?>
    <div class="bg-white-neutral p-[16px] rounded-[20px]">
        <!-- Image -->
        <div class="w-full h-[240px] rounded-[16px] overflow-hidden mb-[16px]">
            <img 
                src="<?= url('images/' . ($item['image'] ?? 'placeholder.jpg')) ?>" 
                alt="<?= $item['title'] ?? '' ?>" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h3 class="font-bold text-[24px] leading-[38px] text-black-soft mb-[16px]">
            <?= $item['title'] ?? '' ?>
        </h3>
        
        <!-- Usia Card -->
        <div class="bg-white-secondary rounded-[12px] h-[80px] pt-[8px] pr-[12px] pb-[8px] pl-[12px] mb-[16px]">
            <h4 class="font-bold text-[16px] leading-[28px] text-black-soft mb-[8px]">
                Usia
            </h4>
            <p class="font-normal text-[16px] leading-[28px] text-black-soft">
                <?= $item['usia'] ?? '' ?>
            </p>
        </div>
        
        <!-- Durasi & Jumlah Murid Cards -->
        <div class="flex gap-[16px]">
            <!-- Durasi Belajar -->
            <div class="flex-1 bg-white-secondary rounded-[12px] h-[80px] pt-[8px] pr-[12px] pb-[8px] pl-[12px]">
                <h4 class="font-bold text-[16px] leading-[28px] text-black-soft mb-[8px]">
                    Durasi Belajar
                </h4>
                <p class="font-normal text-[16px] leading-[28px] text-black-soft">
                    <?= $item['durasi'] ?? '' ?>
                </p>
            </div>
            
            <!-- Jumlah Murid -->
            <div class="flex-1 bg-white-secondary rounded-[12px] h-[80px] pt-[8px] pr-[12px] pb-[8px] pl-[12px]">
                <h4 class="font-bold text-[16px] leading-[28px] text-black-soft mb-[8px]">
                    Jumlah Murid
                </h4>
                <p class="font-normal text-[16px] leading-[28px] text-black-soft">
                    <?= $item['jumlah_murid'] ?? '' ?>
                </p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
