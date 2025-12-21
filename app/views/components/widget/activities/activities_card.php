<?php
/**
 * Activities Card Widget Component
 * 
 * Displays program cards with image, title, description and button
 * 
 * @param array $programs - Array of program data with keys: image, title, description
 * @param string $floatingVector - Optional floating vector image path for top right corner (relative to images/vectors/)
 * @param string $floatingVectorCenter - Optional floating vector image path for center of second row (relative to images/vectors/)
 */

$programs = $programs ?? [];
$floatingVector = $floatingVector ?? null;
$floatingVectorCenter = $floatingVectorCenter ?? null;
?>

<div class="relative">
    <?php if ($floatingVector): ?>
    <!-- Floating Vector Top Right -->
    <img 
        src="<?= url('images/vectors/' . $floatingVector) ?>" 
        alt="" 
        class="absolute -right-[52px] -top-[55px] w-[64px] h-[70px] z-10 pointer-events-none"
    >
    <?php endif; ?>
    
    <?php if ($floatingVectorCenter): ?>
    <!-- Floating Vector Center Second Row -->
    <img 
        src="<?= url('images/vectors/' . $floatingVectorCenter) ?>" 
        alt="" 
        class="absolute left-1/2 translate-x-[400px] top-[50%] translate-y-[120px] w-[90px] h-[90px] z-10 pointer-events-none"
    >
    <?php endif; ?>
    
    <div class="grid grid-cols-3 gap-[24px]">
    <?php foreach ($programs as $item): ?>
    <div class="bg-white-neutral p-[16px] rounded-[20px] flex flex-col h-full">
        <!-- Image -->
        <div class="w-full h-[184px] rounded-[16px] overflow-hidden mb-[16px] flex-shrink-0 bg-gray-placeholder relative">
            <img 
                src="<?= $item['image'] ?? url('images/placeholder.jpg') ?>" 
                alt="<?= $item['title'] ?? '' ?>" 
                class="w-full h-full object-cover"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
            >
            <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <!-- Title -->
        <h3 class="font-bold text-[24px] leading-[38px] text-black-soft mb-[16px]">
            <?= $item['title'] ?? '' ?>
        </h3>
        
        <!-- Description -->
        <p class="font-normal text-[20px] leading-[32px] text-black-soft flex-grow">
            <?= $item['description'] ?? '' ?>
        </p>
        
        <!-- Button -->
        <div class="mt-auto pt-[30px]">
            <?php component('button', ['text' => 'Lihat Galeri', 'variant' => '7', 'href' => url('/activities-gallery')]); ?>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
</div>
