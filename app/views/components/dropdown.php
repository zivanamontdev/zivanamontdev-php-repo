<?php
/**
 * Dropdown Component
 * 
 * @param string $id - Unique ID for the dropdown (required)
 * @param array $options - Array of options ['value' => 'Label'] (required)
 * @param string $selected - Currently selected value (optional, defaults to first option)
 * @param string $name - Form input name (optional)
 * @param string $class - Additional wrapper classes (optional)
 * @param string $variant - compact (default) or form for full-size form fields
 * @param string $inputId - Hidden input ID; trigger uses $id . '-trigger'
 */

$id = $id ?? 'dropdown-' . uniqid();
$options = $options ?? [];
$selected = $selected ?? (count($options) > 0 ? array_key_first($options) : '');
$name = $name ?? '';
$class = $class ?? '';
$variant = $variant ?? 'compact';
$inputId = $inputId ?? '';
$isForm = $variant === 'form';

$selectedLabel = $options[$selected] ?? '';
?>

<div id="<?= e($id) ?>" class="relative inline-block <?= $class ?>" data-dropdown>
    <?php if ($name): ?>
        <input type="hidden" <?= $inputId ? 'id="' . e($inputId) . '"' : '' ?> name="<?= e($name) ?>" value="<?= e($selected) ?>" data-dropdown-input>
    <?php endif; ?>
    
    <!-- Dropdown Button -->
    <button type="button" 
            id="<?= e($id) ?>-trigger" aria-expanded="false" aria-controls="<?= e($id) ?>-menu"
            class="flex items-center bg-white-neutral border cursor-pointer hover:border-primary transition-colors <?= $isForm ? 'w-full h-[52px] justify-between gap-3 px-[24px] py-[12px] border-border-light rounded-xl focus:outline-none focus:border-primary' : 'gap-1 px-2 py-2 border-[#E0E0E0] rounded-[8px]' ?>"
            data-dropdown-trigger>
        <span class="<?= $isForm ? 'text-[16px] leading-[28px] text-black-soft' : 'text-[12px] text-black-highlight' ?> font-normal whitespace-nowrap" data-dropdown-label><?= e($selectedLabel) ?></span>
        <svg class="w-3 h-3 text-black-highlight transition-transform" data-dropdown-icon width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    
    <!-- Dropdown Menu -->
    <div id="<?= e($id) ?>-menu" class="absolute top-full left-0 mt-1 bg-white-neutral border border-[#E0E0E0] rounded-[8px] shadow-lg z-50 hidden min-w-full <?= $isForm ? 'max-h-[240px] overflow-y-auto' : '' ?>"
         data-dropdown-menu>
        <?php foreach ($options as $value => $label): ?>
            <button type="button"
                    class="w-full px-3 py-2 text-left <?= $isForm ? 'text-[16px] leading-[28px] text-black-soft' : 'text-[12px] text-black-highlight' ?> hover:bg-white-secondary focus:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px] <?= $value === $selected ? 'hidden' : '' ?>"
                    data-dropdown-option="<?= e($value) ?>"
                    data-dropdown-option-label="<?= e($label) ?>">
                <?= e($label) ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<script>
(function() {
    const dropdown = document.getElementById('<?= e($id) ?>');
    if (!dropdown) return;
    
    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');
    const label = dropdown.querySelector('[data-dropdown-label]');
    const icon = dropdown.querySelector('[data-dropdown-icon]');
    const input = dropdown.querySelector('[data-dropdown-input]');
    const options = dropdown.querySelectorAll('[data-dropdown-option]');
    const initialValue = input?.value;
    
    let isOpen = false;
    
    function toggleDropdown() {
        isOpen = !isOpen;
        menu.classList.toggle('hidden', !isOpen);
        icon.classList.toggle('rotate-180', isOpen);
        trigger.setAttribute('aria-expanded', String(isOpen));
    }
    
    function closeDropdown() {
        isOpen = false;
        menu.classList.add('hidden');
        icon.classList.remove('rotate-180');
        trigger.setAttribute('aria-expanded', 'false');
    }
    
    function selectOption(value, optionLabel, emit = true) {
        // Update label
        label.textContent = optionLabel;
        
        // Update hidden input
        if (input) {
            input.value = value;
        }
        
        // Show all options, hide selected one
        options.forEach(opt => {
            if (opt.dataset.dropdownOption === value) {
                opt.classList.add('hidden');
            } else {
                opt.classList.remove('hidden');
            }
        });
        
        // Close dropdown
        closeDropdown();
        
        // Dispatch change event
        if (emit) {
            input?.dispatchEvent(new Event('change', {bubbles: true}));
            dropdown.dispatchEvent(new CustomEvent('dropdown:change', {
                detail: { value, label: optionLabel }
            }));
        }
    }

    function syncValue(value) {
        const option = [...options].find(option => option.dataset.dropdownOption === value);
        selectOption(value, option?.dataset.dropdownOptionLabel ?? value, false);
    }
    input?.addEventListener('change', () => syncValue(input.value));
    input?.form?.addEventListener('reset', () => syncValue(initialValue));

    dropdown.addEventListener('keydown', event => {
        if (!['ArrowDown', 'ArrowUp', 'Escape'].includes(event.key)) return;
        event.preventDefault();
        if (event.key === 'Escape') {
            closeDropdown();
            trigger.focus();
            return;
        }
        if (!isOpen) toggleDropdown();
        const available = [...options].filter(option => !option.classList.contains('hidden'));
        const current = available.indexOf(document.activeElement);
        const next = current < 0 ? (event.key === 'ArrowDown' ? 0 : available.length - 1)
            : (current + (event.key === 'ArrowDown' ? 1 : -1) + available.length) % available.length;
        available[next]?.focus();
    });
    
    // Toggle on trigger click
    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });
    
    // Select option on click
    options.forEach(option => {
        option.addEventListener('click', (e) => {
            e.stopPropagation();
            selectOption(option.dataset.dropdownOption, option.dataset.dropdownOptionLabel);
            trigger.focus();
        });
    });
    
    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            closeDropdown();
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });
})();
</script>
