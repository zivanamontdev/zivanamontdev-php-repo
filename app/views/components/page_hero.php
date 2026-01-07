<?php
/**
 * Page Hero Component
 * 
 * A reusable hero section with background mask and responsive design.
 * 
 * @param string $title - The hero title text
 * @param string $variant - 'primary' (red bg, white text) or 'secondary' (yellow bg, black text)
 */

$title = $title ?? 'Page Title';
$variant = $variant ?? 'primary';

// Variant configurations
$variants = [
    'primary' => [
        'bg' => 'bg-primary',
        'textColor' => 'text-white-neutral'
    ],
    'secondary' => [
        'bg' => 'bg-secondary',
        'textColor' => 'text-black-soft'
    ]
];

$config = $variants[$variant] ?? $variants['primary'];
?>

<section class="container mx-auto px-5 mt-[52px]">
    <div class="<?= $config['bg'] ?> rounded-[32px] h-[100px] md:h-[132px] px-[12px] py-[16px] md:p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[20px] md:text-[40px] leading-[32px] md:leading-[140%] <?= $config['textColor'] ?> text-center">
            <?= e($title) ?>
        </h1>
    </div>
</section>