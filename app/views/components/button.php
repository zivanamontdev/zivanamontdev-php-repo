<?php
/**
 * Button Component
 * 
 * @param string $text - Button text
 * @param string $variant - Button variant (1, 2, 3, 4)
 * @param string $href - Optional link URL (renders as <a> if provided)
 * @param string $type - Button type for form (button, submit, reset)
 * @param string $class - Additional CSS classes
 * @param string $id - Button ID
 * @param bool $disabled - Disabled state
 * @param array $attrs - Additional HTML attributes
 */

$text = $text ?? 'Button';
$variant = $variant ?? '1';
$href = $href ?? null;
$type = $type ?? 'button';
$class = $class ?? '';
$id = $id ?? '';
$disabled = $disabled ?? false;
$attrs = $attrs ?? [];

// Base styles (shared)
$baseStyles = 'inline-flex items-center justify-center font-bold transition-all duration-200';

// Variant styles
$variants = [
    // Variant 1: Large primary button
    '1' => [
        'padding' => 'py-3 px-6',           // 12px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-primary',                // #C92C2F
        'text' => 'text-white',              // #FFFFFF
        'font' => 'font-bold text-base',     // 700, 16px
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 2: Small accent button
    '2' => [
        'padding' => 'py-2 px-[13px]',       // 8px top/bottom, 13px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-pale-accent',            // #FEDBA9
        'text' => '!text-[#59360F]',         // #59360F (forced with !)
        'font' => 'font-normal text-base',   // 400, 16px
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 3: Normal weight button
    '3' => [
        'padding' => 'py-2 px-6',            // 8px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-pale-accent',            // #FEDBA9
        'text' => 'text-black-soft',         // #151419
        'font' => 'font-normal text-base',   // 400, 16px
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 4: Back button with arrow icon
    '4' => [
        'padding' => 'py-2 px-6',            // 8px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-pale-accent',            // #FEDBA9
        'text' => 'text-black-soft',         // #151419
        'font' => 'font-normal text-base',   // 400, 16px
        'icon' => 'arrow_left',
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 5: Disabled-style button with 50% opacity
    '5' => [
        'padding' => 'py-2 px-6',            // 8px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-neutral',          // #FCFCFD
        'text' => 'text-black-soft',         // #151419
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'opacity' => 'opacity-50',
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 6: White neutral button
    '6' => [
        'padding' => 'py-3 px-6',            // 12px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-neutral',          // #FCFCFD
        'text' => 'text-black-soft',         // #151419
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 7: Text button with chevron right
    '7' => [
        'padding' => 'py-3 px-3',            // 12px all around
        'radius' => 'rounded-xl',            // 12px for hover background
        'bg' => 'bg-transparent',            // transparent
        'text' => 'text-primary',            // #C92C2F
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'icon' => 'chevron_right',
        'fullWidth' => true,
        'hover' => 'hover:bg-white-secondary',
    ],
    
    // Variant 8: Add button with plus icon
    '8' => [
        'padding' => 'py-[10px] px-[12px]',  // 10px top/bottom, 12px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-secondary',        // white_secondary
        'text' => 'text-black-highlight',    // black_highlight
        'font' => 'font-normal text-[12px] leading-[100%]', // 400, 12px, line-height 100%
        'icon' => 'plus',
        'gap' => 'gap-2',                    // 8px gap
        'hover' => 'hover:opacity-80 active:scale-95',
    ],
];

// Get variant config
$config = $variants[$variant] ?? $variants['1'];

// Build classes
$classes = implode(' ', array_filter([
    $baseStyles,
    $config['padding'],
    $config['radius'],
    $config['bg'],
    $config['text'],
    $config['font'],
    $config['opacity'] ?? null,
    $config['gap'] ?? null,
    $config['hover'] ?? '',
    isset($config['fullWidth']) && $config['fullWidth'] ? 'w-full justify-between' : '',
    $class,
    $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
]));

// Build additional attributes string
$attrString = '';
if ($id) {
    $attrString .= ' id="' . e($id) . '"';
}
if ($disabled) {
    $attrString .= ' disabled';
}
foreach ($attrs as $key => $value) {
    $attrString .= ' ' . e($key) . '="' . e($value) . '"';
}

// Arrow left icon SVG
$arrowLeftIcon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>';

// Chevron right icon SVG (16x16, primary color)
$chevronRightIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';

// Plus icon SVG (10x10, black_highlight)
$plusIcon = '<svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>';

// Render button
if ($href): ?>
    <a href="<?= e($href) ?>" class="btn-component <?= $classes ?>"<?= $attrString ?>>
        <?php if (isset($config['icon']) && $config['icon'] === 'arrow_left'): ?>
            <?= $arrowLeftIcon ?>
        <?php endif; ?>
        <?php if (isset($config['icon']) && $config['icon'] === 'plus'): ?>
            <?= $plusIcon ?>
        <?php endif; ?>
        <?= e($text) ?>
        <?php if (isset($config['icon']) && $config['icon'] === 'chevron_right'): ?>
            <?= $chevronRightIcon ?>
        <?php endif; ?>
    </a>
<?php else: ?>
    <button type="<?= e($type) ?>" class="btn-component <?= $classes ?>"<?= $attrString ?>>
        <?php if (isset($config['icon']) && $config['icon'] === 'arrow_left'): ?>
            <?= $arrowLeftIcon ?>
        <?php endif; ?>
        <?php if (isset($config['icon']) && $config['icon'] === 'plus'): ?>
            <?= $plusIcon ?>
        <?php endif; ?>
        <?= e($text) ?>
        <?php if (isset($config['icon']) && $config['icon'] === 'chevron_right'): ?>
            <?= $chevronRightIcon ?>
        <?php endif; ?>
    </button>
<?php endif; ?>
