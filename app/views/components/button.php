<?php

$text = $text ?? 'Button';
$variant = $variant ?? '1';
$href = $href ?? null;
$type = $type ?? 'button';
$class = $class ?? '';
$id = $id ?? '';
$disabled = $disabled ?? false;
$attrs = $attrs ?? [];
$icon = $icon ?? 'edit'; // default icon for variant 9
$iconPosition = $iconPosition ?? 'left'; // left or right

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
        'border' => 'border border-border-light', // 1px border with border_light
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
        'border' => 'border border-border-light', // 1px border with border_light
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
    
    // Variant 9: Icon only button (edit, etc)
    '9' => [
        'padding' => 'p-[10px]',             // 10px all around
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-secondary',        // white_secondary
        'text' => 'text-black-highlight',    // black_highlight
        'font' => '',                        // no text, icon only
        'iconOnly' => true,
        'hover' => 'hover:opacity-80 active:scale-95',
    ],
    
    // Variant 10: Disabled button
    '10' => [
        'padding' => 'py-[12px] px-[24px]',  // 12px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-secondary',        // white_secondary
        'text' => 'text-white-shadow',       // white_shadow
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'border' => 'border border-border-light', // 1px border
        'height' => 'h-[52px]',
        'hover' => '',                       // no hover for disabled
        'disabled' => true,                  // always disabled
    ],
    
    // Variant 11: Border button without icon
    '11' => [
        'padding' => 'py-3 px-6',            // 12px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-white-neutral',          // white_neutral
        'text' => 'text-primary',            // primary
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'border' => 'border border-border-light', // 1px border with border_light
        'hover' => 'hover:bg-white-secondary',
    ],
    
    // Variant 12: Secondary background button (same size as variant 1)
    '12' => [
        'padding' => 'py-3 px-6',            // 12px top/bottom, 24px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-secondary',              // #F39C12
        'text' => 'text-white',              // #FFFFFF
        'font' => 'font-bold text-base',     // 700, 16px
        'hover' => 'hover:opacity-90 active:scale-95',
    ],
    
    // Variant 13: Text button with left icon (variant 7 style without chevron)
    '13' => [
        'padding' => 'py-[12px] px-3',       // 12px top/bottom, 12px left/right
        'radius' => 'rounded-xl',            // 12px for hover background
        'bg' => 'bg-transparent',            // transparent
        'text' => 'text-primary',            // #C92C2F
        'font' => 'font-normal text-base leading-[28px]', // 400, 16px, line-height 28px
        'icon' => 'left',                    // icon on left side
        'gap' => 'gap-[6px]',                // 6px gap
        'border' => 'border border-border-light', // 1px border with border_light
        'hover' => 'hover:bg-white-secondary',
    ],
    
    // Variant 14: Outline back button with arrow left icon
    '14' => [
        'padding' => 'py-[9px] px-[10px]',   // 9px top/bottom, 10px left/right
        'radius' => 'rounded-xl',            // 12px
        'bg' => 'bg-transparent',            // no background
        'text' => 'text-black-highlight',    // black_highlight
        'font' => 'font-normal text-[12px]', // 400, 12px
        'icon' => 'arrow_left',              // arrow left icon
        'gap' => 'gap-2',                    // 8px gap between icon and text
        'border' => 'border border-border-light', // 1px border with border_light
        'hover' => 'hover:bg-white-secondary active:scale-95',
    ],
];

// Get variant config
$config = $variants[$variant] ?? $variants['1'];

// Override disabled state for variant 10
if ($variant === '10') {
    $disabled = true;
}

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
    $config['border'] ?? null,
    $config['height'] ?? null,
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

// Arrow left icon SVG (16x16 for variant 14, dynamic size)
$arrowLeftIconSize = ($variant === '14') ? 'w-4 h-4' : 'w-5 h-5 mr-2';
$arrowLeftIcon = '<svg class="' . $arrowLeftIconSize . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>';

// Chevron right icon SVG (16x16, primary color)
$chevronRightIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';

// Plus icon SVG (10x10, black_highlight)
$plusIcon = '<svg class="w-[10px] h-[10px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>';

// Edit icon SVG (16x16, black_highlight) - outline style
$editIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';

// Delete icon SVG (16x16, black_highlight) - outline style
$deleteIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';

// Trash icon SVG (16x16, primary color) - outline style
$trashIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';

// Get icon for variant 9
$iconMap = [
    'edit' => $editIcon,
    'delete' => $deleteIcon,
    'plus' => $plusIcon,
    'trash' => $trashIcon,
];
$selectedIcon = $iconMap[$icon] ?? $editIcon;

// Render button
if ($href): ?>
    <a href="<?= e($href) ?>" class="btn-component <?= $classes ?>"<?= $attrString ?>>
        <?php if (isset($config['iconOnly']) && $config['iconOnly']): ?>
            <?= $selectedIcon ?>
        <?php else: ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'arrow_left'): ?>
                <?= $arrowLeftIcon ?>
            <?php endif; ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'plus'): ?>
                <?= $plusIcon ?>
            <?php endif; ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'left'): ?>
                <?= $selectedIcon ?>
            <?php endif; ?>
            <?= e($text) ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'chevron_right'): ?>
                <?= $chevronRightIcon ?>
            <?php endif; ?>
        <?php endif; ?>
    </a>
<?php else: ?>
    <button type="<?= e($type) ?>" class="btn-component <?= $classes ?>"<?= $attrString ?>>
        <?php if (isset($config['iconOnly']) && $config['iconOnly']): ?>
            <?= $selectedIcon ?>
        <?php else: ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'arrow_left'): ?>
                <?= $arrowLeftIcon ?>
            <?php endif; ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'plus'): ?>
                <?= $plusIcon ?>
            <?php endif; ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'left'): ?>
                <?= $selectedIcon ?>
            <?php endif; ?>
            <?= e($text) ?>
            <?php if (isset($config['icon']) && $config['icon'] === 'chevron_right'): ?>
                <?= $chevronRightIcon ?>
            <?php endif; ?>
        <?php endif; ?>
    </button>
<?php endif; ?>
