<?php
/**
 * Modal Delete Confirmation Component
 * Reusable delete confirmation modal
 * 
 * Parameters:
 * - $modalId: unique ID for the modal (required)
 * - $title: modal title text (optional, can be set dynamically)
 * - $description: modal description text (optional, can be set dynamically)
 */

$modalId = $modalId ?? 'modal-delete-confirmation';
$title = $title ?? 'Hapus Item';
$description = $description ?? 'Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.';
?>

<!-- Modal Backdrop -->
<div id="<?= e($modalId) ?>" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[329px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[140%] text-black-soft" id="<?= e($modalId) ?>-title">
                <?= e($title) ?>
            </h3>
            <button type="button" id="close-<?= e($modalId) ?>" class="text-black-highlight hover:text-black-soft transition-colors ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Description -->
        <div class="mb-[32px]">
            <p class="font-normal text-[16px] leading-[26px] text-black-soft" id="<?= e($modalId) ?>-description">
                <?= e($description) ?>
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-[12px]">
            <!-- Cancel Button -->
            <?php component('button', [
                'text' => 'Batalkan',
                'variant' => '6',
                'type' => 'button',
                'id' => 'cancel-' . $modalId,
                'class' => 'flex-1'
            ]); ?>
            
            <!-- Delete Button -->
            <?php component('button', [
                'text' => 'Ya, Hapus',
                'variant' => '17',
                'type' => 'button',
                'id' => 'confirm-' . $modalId,
                'class' => 'flex-1'
            ]); ?>
        </div>
    </div>
</div>

<!-- Modal Script -->
<script>
(function() {
    const modalId = '<?= e($modalId) ?>';
    
    // Function to open delete confirmation modal
    window['open' + modalId.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join('')] = function(title, description, onConfirm) {
        const modal = document.getElementById(modalId);
        const titleElement = document.getElementById(modalId + '-title');
        const descriptionElement = document.getElementById(modalId + '-description');
        
        if (title && titleElement) {
            titleElement.textContent = title;
        }
        if (description && descriptionElement) {
            descriptionElement.textContent = description;
        }
        
        modal.classList.remove('hidden');
        
        // Store the callback for confirmation
        window['__' + modalId + '_callback'] = onConfirm;
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById(modalId);
        const closeBtn = document.getElementById('close-' + modalId);
        const cancelBtn = document.getElementById('cancel-' + modalId);
        const confirmBtn = document.getElementById('confirm-' + modalId);
        
        // Close modal function
        function closeModal() {
            modal.classList.add('hidden');
            // Clear the callback
            window['__' + modalId + '_callback'] = null;
        }
        
        // Close button
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        // Cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }
        
        // Confirm button
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const callback = window['__' + modalId + '_callback'];
                if (callback && typeof callback === 'function') {
                    callback();
                }
                closeModal();
            });
        }
        
        // Close modal on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
})();
</script>
