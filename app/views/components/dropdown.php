<?php
/**
 * Dropdown Component
 * 
 * @param string $id - Unique ID for the dropdown (required)
 * @param array $options - Array of options ['value' => 'Label'] (required)
 * @param string $selected - Currently selected value (optional, defaults to first option)
 * @param string $name - Form input name (optional)
 * @param string $class - Additional wrapper classes (optional)
 */

$id = $id ?? 'dropdown-' . uniqid();
$options = $options ?? [];
$selected = $selected ?? (count($options) > 0 ? array_key_first($options) : '');
$name = $name ?? '';
$class = $class ?? '';

$selectedLabel = $options[$selected] ?? '';
?>

<div id="<?= e($id) ?>" class="relative inline-block <?= $class ?>" data-dropdown>
    <?php if ($name): ?>
        <input type="hidden" name="<?= e($name) ?>" value="<?= e($selected) ?>" data-dropdown-input>
    <?php endif; ?>
    
    <!-- Dropdown Button -->
    <button type="button" 
            class="flex items-center gap-1 px-2 py-2 bg-white-neutral border border-[#E0E0E0] rounded-[8px] cursor-pointer hover:border-primary transition-colors"
            data-dropdown-trigger>
        <span class="text-[12px] font-normal text-black-highlight whitespace-nowrap" data-dropdown-label><?= e($selectedLabel) ?></span>
        <svg class="w-3 h-3 text-black-highlight transition-transform" data-dropdown-icon width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    
    <!-- Dropdown Menu -->
    <div class="absolute top-full left-0 mt-1 bg-white-neutral border border-[#E0E0E0] rounded-[8px] shadow-lg z-50 hidden min-w-full"
         data-dropdown-menu>
        <?php foreach ($options as $value => $label): ?>
            <button type="button"
                    class="w-full px-3 py-2 text-left text-[12px] text-black-highlight hover:bg-white-secondary transition-colors first:rounded-t-[8px] last:rounded-b-[8px] <?= $value === $selected ? 'hidden' : '' ?>"
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
    
    let isOpen = false;
    
    function toggleDropdown() {
        isOpen = !isOpen;
        menu.classList.toggle('hidden', !isOpen);
        icon.classList.toggle('rotate-180', isOpen);
    }
    
    function closeDropdown() {
        isOpen = false;
        menu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
    
    function selectOption(value, optionLabel) {
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
        dropdown.dispatchEvent(new CustomEvent('dropdown:change', {
            detail: { value, label: optionLabel }
        }));
    }
    
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
