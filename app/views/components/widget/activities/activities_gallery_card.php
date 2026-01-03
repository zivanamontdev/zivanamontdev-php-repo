<?php
/**
 * Gallery Card Widget Component
 * 
 * Displays gallery cards with image and description (truncated to 2 lines)
 * Clicking on a card opens a modal with full image and description
 * 
 * @param array $items - Array of gallery data with keys: image, description
 */

$items = $items ?? [];

// Helper function to get activity gallery image URL
if (!function_exists('getActivityGalleryImageUrl')) {
    function getActivityGalleryImageUrl($imagePath) {
        if (empty($imagePath)) {
            return url('images/placeholder.jpg');
        }
        // If path is already a full URL (http:// or https://), return as-is
        if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0) {
            return $imagePath;
        }
        // If path already includes 'uploads/', use it directly
        if (strpos($imagePath, 'uploads/') === 0) {
            return url($imagePath);
        }
        return url('uploads/' . $imagePath);
    }
}
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]" x-data="{ showModal: false, currentImage: '', currentDescription: '' }">
    <?php foreach ($items as $index => $item): ?>
    <div 
        class="bg-white-neutral p-[16px] rounded-[20px] cursor-pointer hover:shadow-lg transition-shadow duration-200"
        @click="showModal = true; currentImage = <?= json_encode(getActivityGalleryImageUrl($item['image'] ?? '')) ?>; currentDescription = <?= json_encode($item['description'] ?? '') ?>"
    >
        <!-- Image -->
        <div class="w-full h-[184px] rounded-[16px] overflow-hidden mb-[16px] bg-gray-placeholder relative">
            <img 
                src="<?= getActivityGalleryImageUrl($item['image'] ?? '') ?>" 
                alt="" 
                class="w-full h-full object-cover"
                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
            >
            <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <!-- Description (max 2 lines with ellipsis) -->
        <p class="font-normal text-[14px] leading-[24px] md:text-[20px] md:leading-[32px] text-black-soft line-clamp-2">
            <?= $item['description'] ?? '' ?>
        </p>
    </div>
    <?php endforeach; ?>
    
    <!-- Modal -->
    <div 
        x-show="showModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center"
        @click.self="showModal = false"
        @keydown.escape.window="showModal = false"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70" @click="showModal = false"></div>
        
        <!-- Modal Content -->
        <div class="relative z-10 max-w-[1116px] w-full mx-4">
            <!-- Header: Preview Foto & Close Button -->
            <div class="flex justify-between items-center mb-[12px]">
                <h3 class="font-bold text-[14px] leading-[24px] md:text-[20px] md:leading-[32px] text-white-neutral">Preview Foto</h3>
                <button @click="showModal = false" class="text-white-neutral hover:opacity-80 transition-opacity">
                    <svg class="w-[24px] h-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Image -->
            <div class="w-full h-[158px] md:h-[626px] rounded-[12px] overflow-hidden mb-[36px] bg-gray-placeholder relative">
                <img 
                    :src="currentImage" 
                    alt="" 
                    class="w-full h-full object-cover"
                    @error="$event.target.style.display='none'; $event.target.nextElementSibling.classList.remove('hidden');"
                >
                <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            
            <!-- Description -->
            <p class="font-normal text-[16px] leading-[28px] md:text-[24px] md:leading-[32px] text-white-neutral text-center" x-text="currentDescription"></p>
        </div>
    </div>
</div>
