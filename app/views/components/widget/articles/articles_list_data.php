<?php
/**
 * Articles List Data Component
 * 
 * @param int $id - Article ID
 * @param string $title - Article title
 * @param string $excerpt - Article excerpt/description
 * @param string $author - Author name
 * @param string $date - Article date (timestamp or date string)
 * @param string $image - Article image URL (optional)
 */

$id = $id ?? 0;
$title = $title ?? 'Article Title';
$excerpt = $excerpt ?? 'Article description goes here...';
$author = $author ?? 'Author Name';
$date = $date ?? date('Y-m-d H:i:s');
$image = $image ?? '';

// Format date to "Kamis, 27 November 2025"
$timestamp = is_numeric($date) ? $date : strtotime($date);
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$dayName = $days[date('w', $timestamp)];
$day = date('j', $timestamp);
$monthName = $months[date('n', $timestamp) - 1];
$year = date('Y', $timestamp);
$formattedDate = "$dayName, $day $monthName $year";
?>

<div class="flex items-start justify-between py-3">
    <!-- Left Content -->
    <div class="flex-1 flex flex-col">
        <!-- Title -->
        <h4 class="font-bold text-[14px] leading-[100%] text-text-dark mb-2"><?= e($title) ?></h4>
        
        <!-- Description -->
        <p class="font-normal text-[12px] leading-[100%] text-black-highlight mb-[20px] overflow-hidden text-ellipsis whitespace-nowrap" style="word-spacing: 1px; max-width: calc(100% - 52px);"><?= e($excerpt) ?></p>
        
        <!-- Author and Date Container -->
        <div class="flex items-center justify-between">
            <!-- Author and Date -->
            <div class="flex items-center gap-2 text-black-highlight font-normal text-[12px] leading-[100%]">
                <span><?= e($author) ?></span>
                <span>•</span>
                <span><?= e($formattedDate) ?></span>
            </div>
            
            <!-- Edit Button -->
            <div class="flex-shrink-0">
                <?php component('button', [
                    'variant' => '9',
                    'icon' => 'edit',
                    'type' => 'button',
                    'id' => 'btn-edit-article-' . $id,
                    'class' => 'w-8 h-8 p-2',
                    'attrs' => [
                        'data-article-id' => $id
                    ]
                ]); ?>
            </div>
        </div>
    </div>
    
    <!-- Right Image Container -->
    <div class="w-[111px] h-[87px] ml-3 flex-shrink-0">
        <div class="w-full h-full rounded-lg bg-gray-placeholder relative overflow-hidden">
            <?php if ($image): ?>
                <img 
                    src="<?= e($image) ?>" 
                    alt="<?= e($title) ?>" 
                    class="w-full h-full object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                >
                <svg class="hidden absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            <?php else: ?>
                <svg class="absolute inset-0 w-8 h-8 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            <?php endif; ?>
        </div>
    </div>
</div>
