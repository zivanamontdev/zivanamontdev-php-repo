<?php
/**
 * Card Component
 * 
 * Wrapper component untuk card dengan background white-neutral
 * Konten diatur di widget masing-masing
 * 
 * @param string $variant - Card variant ('1' = default, '2' = home card)
 * @param string $class - Additional CSS classes (optional)
 */

$variant = $variant ?? '1';
$class = $class ?? '';

// Variant styles
$variants = [
    '1' => 'p-[16px] rounded-[20px]',  // default
    '2' => 'p-[24px] rounded-[32px]',  // home card
];

$variantClass = $variants[$variant] ?? $variants['1'];
?>
