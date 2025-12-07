<?php
/**
 * Tab Content Card Component
 * 
 * @param string $title - Card title
 * @param string $description - Card description
 * @param string $buttonText - Button text (optional, if empty no button shown)
 * @param string $buttonHref - Button link URL (optional)
 * @param string $buttonId - Button ID for JS handling (optional)
 * @param string $slot - Additional content to render inside the card
 */

$title = $title ?? '';
$description = $description ?? '';
$buttonText = $buttonText ?? '';
$buttonHref = $buttonHref ?? '#';
$buttonId = $buttonId ?? '';
$slot = $slot ?? '';
?>

<div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
    <!-- Header: Title, Description and Button -->
    <div class="flex items-center justify-between">
        <!-- Title and Description -->
        <div>
            <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]"><?= e($title) ?></h3>
            <p class="font-normal text-[14px] leading-[21px] text-white-soft"><?= e($description) ?></p>
        </div>
        
        <!-- Add Button (only if buttonText is provided) -->
        <?php if (!empty($buttonText)): ?>
        <div class="flex-shrink-0">
            <?php component('button', [
                'text' => $buttonText,
                'variant' => '8',
                'href' => $buttonHref,
                'id' => $buttonId
            ]); ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Slot for additional content -->
    <?php if (!empty($slot)): ?>
    <div class="mt-4">
        <?= $slot ?>
    </div>
    <?php endif; ?>
</div>
