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
        src="<?= asset('images/vectors/vector_highlight_kelas.png') ?>" 
        alt="" 
        class="hidden md:block absolute -left-[54px] -top-[57px] w-[68px] h-[74px] z-10 pointer-events-none"
    >
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-[24px]">
    <?php foreach ($kelas as $item): ?>
    <div class="bg-white-neutral p-[16px] rounded-[20px]">
        <!-- Image -->
        <div class="w-full h-[240px] rounded-[16px] overflow-hidden mb-[16px] bg-gray-placeholder relative">
            <img 
                src="<?= $item['image'] ?? asset('images/placeholder.jpg') ?>" 
                alt="<?= $item['title'] ?? '' ?>" 
                class="w-full h-full object-cover"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
            >
            <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <!-- Title -->
        <h3 class="font-bold text-[20px] lg:text-[24px] leading-[32px] lg:leading-[38px] text-black-soft mb-[16px]">
            <?= $item['title'] ?? '' ?>
        </h3>
        
        <!-- Usia Card -->
        <div class="bg-white-secondary rounded-[12px] h-[80px] px-[12px] py-[8px] mb-[16px] flex flex-col justify-center">
            <h4 class="font-bold text-[12px] lg:text-[16px] leading-[20px] lg:leading-[28px] text-black-soft mb-[4px]">
                Usia
            </h4>
            <p class="font-normal text-[14px] lg:text-[16px] leading-[24px] lg:leading-[28px] text-black-soft">
                <?= $item['usia'] ?? '' ?>
            </p>
        </div>
        
        <!-- Durasi & Jumlah Murid Cards -->
        <div class="flex gap-[16px]">
            <!-- Durasi Belajar -->
            <div class="flex-1 bg-white-secondary rounded-[12px] h-[80px] px-[12px] py-[8px] flex flex-col justify-center">
                <h4 class="font-bold text-[12px] lg:text-[16px] leading-[20px] lg:leading-[28px] text-black-soft mb-[4px]">
                    Durasi Belajar
                </h4>
                <p class="font-normal text-[14px] lg:text-[16px] leading-[24px] lg:leading-[28px] text-black-soft">
                    <?= $item['durasi'] ?? '' ?>
                </p>
            </div>
            
            <!-- Jumlah Murid -->
            <div class="flex-1 bg-white-secondary rounded-[12px] h-[80px] px-[12px] py-[8px] flex flex-col justify-center">
                <h4 class="font-bold text-[12px] lg:text-[16px] leading-[20px] lg:leading-[28px] text-black-soft mb-[4px]">
                    Jumlah Murid
                </h4>
                <p class="font-normal text-[14px] lg:text-[16px] leading-[24px] lg:leading-[28px] text-black-soft">
                    <?= $item['jumlah_murid'] ?? '' ?>
                </p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
