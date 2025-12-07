<?php
/**
 * Input Component
 * 
 * @param string $name - Input name attribute (required)
 * @param string $type - Input type (text, email, tel, password, etc.) - default: text
 * @param string $variant - Input variant: 'default' or 'search' - default: default
 * @param string $label - Label text (optional)
 * @param string $placeholder - Placeholder text (optional)
 * @param string $value - Input value (optional)
 * @param bool $required - Whether input is required - default: false
 * @param string $id - Input ID (defaults to name)
 * @param string $icon - Icon type: 'search', 'user', 'mail', 'phone', 'lock', 'home' (optional)
 * @param string $iconPosition - 'left' or 'right' - default: left
 * @param string $class - Additional classes for the wrapper (optional)
 * @param string $inputClass - Additional classes for the input (optional)
 */

$name = $name ?? '';
$type = $type ?? 'text';
$variant = $variant ?? 'default';
$label = $label ?? '';
$placeholder = $placeholder ?? '';
$value = $value ?? '';
$required = $required ?? false;
$id = $id ?? $name;
$icon = $icon ?? '';
$iconPosition = $iconPosition ?? 'left';
$class = $class ?? '';
$inputClass = $inputClass ?? '';

// Auto-set icon for search variant
if ($variant === 'search' && empty($icon)) {
    $icon = 'search';
}

// Icon SVGs
$icons = [
    'search' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
    
    'user' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 8C9.65685 8 11 6.65685 11 5C11 3.34315 9.65685 2 8 2C6.34315 2 5 3.34315 5 5C5 6.65685 6.34315 8 8 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M13.5 14C13.5 11.79 11.09 10 8 10C4.91 10 2.5 11.79 2.5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
    
    'mail' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.66667 2.66667H13.3333C14.0667 2.66667 14.6667 3.26667 14.6667 4V12C14.6667 12.7333 14.0667 13.3333 13.3333 13.3333H2.66667C1.93333 13.3333 1.33333 12.7333 1.33333 12V4C1.33333 3.26667 1.93333 2.66667 2.66667 2.66667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M14.6667 4L8 8.66667L1.33333 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
    
    'phone' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14.6467 12.22C14.6467 12.42 14.6 12.6267 14.5 12.8267C14.4 13.0267 14.2733 13.2133 14.1067 13.3867C13.82 13.6933 13.5 13.9133 13.1333 14.0533C12.7733 14.1933 12.38 14.2667 11.9533 14.2667C11.3267 14.2667 10.66 14.12 9.96 13.82C9.26 13.52 8.56 13.12 7.86667 12.62C7.16667 12.1133 6.50667 11.5533 5.88 10.9333C5.26 10.3067 4.7 9.64667 4.2 8.95333C3.70667 8.26 3.30667 7.56667 3.01333 6.88C2.72 6.18667 2.57333 5.52 2.57333 4.88C2.57333 4.46667 2.64 4.07333 2.77333 3.71333C2.90667 3.34667 3.12 3.01333 3.42 2.72C3.78 2.36667 4.17333 2.19333 4.59333 2.19333C4.75333 2.19333 4.91333 2.22667 5.06 2.29333C5.21333 2.36 5.35333 2.46 5.46667 2.60667L6.82 4.50667C6.93333 4.64667 7.01333 4.77333 7.06667 4.89333C7.12 5.00667 7.15333 5.12 7.15333 5.22C7.15333 5.35333 7.10667 5.48667 7.01333 5.61333C6.92667 5.74 6.8 5.87333 6.64 6.00667L6.2 6.46C6.13333 6.52667 6.10667 6.60667 6.10667 6.70667C6.10667 6.75333 6.11333 6.79333 6.12667 6.84C6.14667 6.88667 6.16667 6.92 6.18 6.95333C6.29333 7.16 6.48667 7.43333 6.76 7.76667C7.04 8.1 7.34 8.44 7.66667 8.78C8.00667 9.12 8.34 9.42667 8.68 9.7C9.01333 9.97333 9.28667 10.16 9.5 10.2733C9.52667 10.2867 9.56667 10.3067 9.62 10.3267C9.68 10.3467 9.74 10.3533 9.80667 10.3533C9.91333 10.3533 9.99333 10.32 10.06 10.2533L10.5067 9.81333C10.6467 9.67333 10.78 9.54667 10.9067 9.46C11.0333 9.36667 11.16 9.32 11.3 9.32C11.4 9.32 11.5067 9.34667 11.62 9.4C11.74 9.45333 11.8667 9.53333 12.0067 9.64L13.9267 11.0133C14.0733 11.1267 14.1733 11.26 14.2333 11.4133C14.2867 11.5667 14.32 11.7133 14.32 11.88L14.6467 12.22Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
    </svg>',
    
    'lock' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4.66667 7.33333V5.33333C4.66667 4.44928 5.01786 3.60143 5.64298 2.97631C6.2681 2.35119 7.11595 2 8 2C8.88406 2 9.7319 2.35119 10.357 2.97631C10.9821 3.60143 11.3333 4.44928 11.3333 5.33333V7.33333M3.33333 7.33333H12.6667C13.403 7.33333 14 7.93029 14 8.66667V13.3333C14 14.0697 13.403 14.6667 12.6667 14.6667H3.33333C2.59695 14.6667 2 14.0697 2 13.3333V8.66667C2 7.93029 2.59695 7.33333 3.33333 7.33333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
    
    'home' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 6L8 1.33333L14 6V13.3333C14 13.687 13.8595 14.0261 13.6095 14.2761C13.3594 14.5262 13.0203 14.6667 12.6667 14.6667H3.33333C2.97971 14.6667 2.64057 14.5262 2.39052 14.2761C2.14048 14.0261 2 13.687 2 13.3333V6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M6 14.6667V8H10V14.6667" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>',
];

$hasIcon = !empty($icon) && isset($icons[$icon]);
$iconSvg = $hasIcon ? $icons[$icon] : '';

// Variant-based styles
if ($variant === 'search') {
    // Search variant: fixed width 264px, smaller font (12px), icon 16x16, compact padding
    $baseInputClasses = "w-[264px] bg-white-neutral border border-[#E0E0E0] rounded-[12px] text-[12px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors";
    
    if ($hasIcon) {
        if ($iconPosition === 'left') {
            $paddingClasses = "pl-[36px] pr-[12px] py-[10px]";
            $iconClasses = "absolute left-[12px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none";
        } else {
            $paddingClasses = "pl-[12px] pr-[36px] py-[10px]";
            $iconClasses = "absolute right-[12px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none";
        }
    } else {
        $paddingClasses = "px-[12px] py-[10px]";
        $iconClasses = "";
    }
} else {
    // Default variant: standard font (16px), standard padding
    $baseInputClasses = "w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] leading-[28px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors";
    
    if ($hasIcon) {
        if ($iconPosition === 'left') {
            $paddingClasses = "pl-[40px] pr-[16px] py-[12px]";
            $iconClasses = "absolute left-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none";
        } else {
            $paddingClasses = "pl-[16px] pr-[40px] py-[12px]";
            $iconClasses = "absolute right-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none";
        }
    } else {
        $paddingClasses = "px-[16px] py-[12px]";
        $iconClasses = "";
    }
}
?>

<div class="<?= $class ?>">
    <?php if ($label): ?>
        <label for="<?= e($id) ?>" class="block font-normal text-[16px] leading-[28px] text-black-soft mb-[8px]">
            <?= e($label) ?>
        </label>
    <?php endif; ?>
    
    <div class="relative">
        <?php if ($hasIcon && $iconPosition === 'left'): ?>
            <span class="<?= $iconClasses ?>">
                <?= $iconSvg ?>
            </span>
        <?php endif; ?>
        
        <input 
            type="<?= e($type) ?>" 
            id="<?= e($id) ?>" 
            name="<?= e($name) ?>"
            placeholder="<?= e($placeholder) ?>"
            value="<?= e($value) ?>"
            <?= $required ? 'required' : '' ?>
            class="<?= $baseInputClasses ?> <?= $paddingClasses ?> <?= $inputClass ?>"
        >
        
        <?php if ($hasIcon && $iconPosition === 'right'): ?>
            <span class="<?= $iconClasses ?>">
                <?= $iconSvg ?>
            </span>
        <?php endif; ?>
    </div>
</div>
