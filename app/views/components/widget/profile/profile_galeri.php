<?php
/**
 * Profile Galeri Card Component
 * 
 * @param string $image - Image filename
 * @param string $title - Card title
 */

$image = $image ?? 'image_fasilitas_1.png';
$title = $title ?? 'Galeri';
?>

<div class="bg-white-neutral rounded-[20px] p-[16px] h-[264px]">
    <!-- Image -->
    <div class="h-[184px] mb-[16px] rounded-[12px] overflow-hidden">
        <img 
            src="<?= url('images/' . $image) ?>" 
            alt="<?= e($title) ?>" 
            class="w-full h-full object-cover object-center"
        >
    </div>
    
    <!-- Title -->
    <h3 class="font-normal text-[20px] leading-[100%] text-black-soft">
        <?= e($title) ?>
    </h3>
</div>
