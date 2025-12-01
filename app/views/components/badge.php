<?php
/**
 * Badge Component
 * 
 * @param string $text - Badge text content
 * @param string $variant - Badge variant (default: '1')
 * @param string $class - Additional CSS classes (optional)
 */

$text = $text ?? '';
$variant = $variant ?? '1';
$class = $class ?? '';

// Variant styles
$variants = [
    '1' => 'bg-white-neutral text-black-soft',
];

$variantClass = $variants[$variant] ?? $variants['1'];
?>

<span class="inline-block py-[8px] px-[20px] rounded-[32px] text-[16px] font-normal leading-[28px] <?= $variantClass ?> <?= $class ?>">
    <?= e($text) ?>
</span>
