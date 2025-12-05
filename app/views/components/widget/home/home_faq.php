<?php
/**
 * Home FAQ Widget
 * 
 * Accordion FAQ section
 * 
 * @param array $faqs - Array of FAQ items with 'question' and 'answer' keys
 */

$faqs = $faqs ?? [];
?>

<div class="flex flex-col gap-[16px]">
    <?php foreach ($faqs as $index => $faq): ?>
        <div class="bg-white-neutral rounded-[24px] p-[24px]" x-data="{ open: false }">
            <!-- Accordion Header -->
            <button 
                @click="open = !open" 
                class="w-full flex items-center justify-between cursor-pointer"
            >
                <h3 class="font-bold text-[20px] leading-[32px] text-black-soft text-left">
                    <?= e($faq['question']) ?>
                </h3>
                <svg 
                    class="w-6 h-6 text-black-soft transition-transform duration-300" 
                    :class="{ 'rotate-180': open }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Accordion Content -->
            <div 
                x-show="open" 
                x-collapse
                class="mt-[16px]"
            >
                <p class="font-normal text-[20px] leading-[32px] text-black-soft">
                    <?= e($faq['answer']) ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
