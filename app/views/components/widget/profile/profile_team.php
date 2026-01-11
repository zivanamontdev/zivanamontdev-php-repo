<?php
/**
 * Profile Team Widget Component
 * 
 * Grid 4x4 untuk menampilkan tim/anggota
 * Card dengan gambar background, nama dan role di bagian bawah
 * 
 * @param string|null $teamImage - Gambar anggota (null untuk placeholder icon)
 * @param string $teamName - Nama anggota
 * @param string $teamRole - Role/jabatan anggota
 * @param bool $teamLarge - Apakah card besar (2x2) atau kecil (1x1)
 */

$teamImage = $teamImage ?? '';
$teamName = $teamName ?? 'Nama Anggota';
$teamRole = $teamRole ?? 'Role';
$teamLarge = $teamLarge ?? false;

// Check if teamImage is null or empty (for placeholder)
$showPlaceholder = empty($teamImage);

// Check if teamImage is already a full URL
$imageUrl = '';
if (!$showPlaceholder) {
    if (strpos($teamImage, 'http://') !== 0 && strpos($teamImage, 'https://') !== 0 && strpos($teamImage, '/') !== 0) {
        // If not a URL, add images/ prefix
        $imageUrl = asset('images/' . $teamImage);
    } else {
        $imageUrl = $teamImage;
    }
}

// Ukuran card
$heightClass = $teamLarge ? 'h-[384px] md:h-[768px]' : 'h-[234px] md:h-[372px]';
$colSpan = $teamLarge ? 'md:col-span-2 md:row-span-2' : '';
?>

<div class="<?= $colSpan ?> rounded-[12px] <?= $heightClass ?> relative overflow-hidden group cursor-pointer team-card-mobile">
    <?php if ($showPlaceholder): ?>
        <!-- Placeholder with Icon -->
        <div class="absolute inset-0 w-full h-full bg-gray-placeholder flex items-center justify-center">
            <svg class="w-24 h-24 text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    <?php else: ?>
        <!-- Background Image with Grayscale -->
        <img 
            src="<?= $imageUrl ?>" 
            alt="<?= e($teamName) ?>" 
            class="absolute inset-0 w-full h-full object-cover object-center grayscale group-hover:grayscale-0 transition-all duration-300 team-card-image"
        >
    <?php endif; ?>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
    
    <!-- Content - Name & Role -->
    <div class="absolute bottom-0 left-0 right-0 p-[24px]">
        <h4 class="font-bold text-[16px] leading-[28px] md:text-[24px] md:leading-[38px] text-white-neutral mb-[4px]">
            <?= e($teamName) ?>
        </h4>
        <p class="font-normal text-[14px] leading-[24px] md:text-[20px] md:leading-[140%] text-white-neutral">
            <?= e($teamRole) ?>
        </p>
    </div>
</div>

<style>
/* Mobile touch/hold state for team cards */
@media (max-width: 767px) {
    .team-card-mobile.touching .team-card-image {
        filter: grayscale(0) !important;
    }
}
</style>

<script>
// Touch event handler for mobile team cards
if (window.matchMedia("(max-width: 767px)").matches) {
    document.addEventListener('DOMContentLoaded', function() {
        const teamCards = document.querySelectorAll('.team-card-mobile');
        
        teamCards.forEach(function(card) {
            // Touch start - show color
            card.addEventListener('touchstart', function(e) {
                this.classList.add('touching');
            });
            
            // Touch end - back to grayscale
            card.addEventListener('touchend', function(e) {
                this.classList.remove('touching');
            });
            
            // Touch cancel - back to grayscale (if touch is interrupted)
            card.addEventListener('touchcancel', function(e) {
                this.classList.remove('touching');
            });
        });
    });
}
</script>
