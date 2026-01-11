<?php
/**
 * FAQ Tab Component
 * Displays FAQ accordion for settings page
 */

// Include delete confirmation modal
component('widget/modal-delete-confirmation', [
    'modalId' => 'modal-delete-faq',
    'title' => 'Hapus FAQ',
    'description' => 'Apakah Anda yakin ingin menghapus FAQ ini? Tindakan ini tidak dapat dibatalkan.'
]);

// Include modals
require VIEW_PATH . '/components/widget/setting/modal/modal-add-faq.php';
require VIEW_PATH . '/components/widget/setting/modal/modal-edit-faq.php';

// Fetch FAQ data from database
try {
    $faqModel = new Faq();
    $faqs = $faqModel->getAll();
} catch (Exception $e) {
    error_log("Error loading FAQs: " . $e->getMessage());
    $faqs = [];
}

// Build FAQ cards HTML for slot
ob_start();
?>
<div id="faq-sortable-list" class="mt-5 space-y-3">
    <?php if (empty($faqs)): ?>
        <div class="text-center py-8 text-white-soft">
            <p>Belum ada FAQ yang ditambahkan</p>
        </div>
    <?php else: ?>
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item rounded-[12px] py-[12px] px-[14px] transition-all flex items-center" data-id="<?= $faq['id'] ?>" data-question="<?= e($faq['question']) ?>" data-answer="<?= e($faq['answer']) ?>" style="background-color: <?= colors('card_bg_light') ?>">
                <!-- Drag Handle (6 dots icon) -->
                <svg class="drag-handle w-[20px] h-[20px] flex-shrink-0 cursor-move mr-[20px]" fill="<?= colors('white_soft') ?>" viewBox="0 0 24 24">
                    <circle cx="7" cy="7" r="1.5"/>
                    <circle cx="7" cy="12" r="1.5"/>
                    <circle cx="7" cy="17" r="1.5"/>
                    <circle cx="12" cy="7" r="1.5"/>
                    <circle cx="12" cy="12" r="1.5"/>
                    <circle cx="12" cy="17" r="1.5"/>
                </svg>
                
                <!-- Content Section -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-[12px] text-black-neutral mb-1"><?= e($faq['question']) ?></h3>
                    <p class="font-normal text-[12px] text-black-highlight leading-[18px]"><?= e($faq['answer']) ?></p>
                </div>
                
                <!-- Right Section: Chevron Right for Edit -->
                <svg class="edit-faq-btn w-[16px] h-[16px] text-black-highlight flex-shrink-0 ml-3 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php
$faqCardsHtml = ob_get_clean();

// Render tab content card with FAQs in slot
component('tab-content-card', [
    'title' => 'Frequently Asked Questions',
    'description' => 'Atur pertanyaan dan jawaban FAQ yang ditampilkan di website utama',
    'buttonText' => 'Tambah FAQ',
    'buttonId' => 'btn-add-faq',
    'buttonIcon' => 'plus',
    'slot' => $faqCardsHtml
]);
?>

<!-- FAQ Sortable Styles -->
<style>
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    cursor: grabbing !important;
}

.sortable-drag {
    opacity: 0.9;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.drag-handle:hover {
    opacity: 0.7;
}
</style>

<!-- FAQ Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Sortable for drag & drop
    const sortableFaqs = document.getElementById('faq-sortable-list');
    if (sortableFaqs && sortableFaqs.querySelector('.faq-item')) {
        const sortable = Sortable.create(sortableFaqs, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function() {
                // Auto save order on drag
                updateFaqOrder();
            }
        });
    }
    
    // Update FAQ order
    function updateFaqOrder() {
        const orders = {};
        const items = document.querySelectorAll('.faq-item');
        items.forEach((item, index) => {
            orders[item.dataset.id] = index + 1;
        });
        
        // Send AJAX request to update order
        fetch('/admin/settings/faqs/update-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'orders=' + encodeURIComponent(JSON.stringify(orders))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Urutan FAQ berhasil diperbarui', 'success', 2000);
            } else {
                console.error('Failed to update FAQ order:', data.message);
                showToast('Gagal mengupdate urutan: ' + data.message, 'error', 5000);
            }
        })
        .catch(error => {
            console.error('Error updating FAQ order:', error);
        });
    }
    
    // Add FAQ button
    const btnAddFaq = document.getElementById('btn-add-faq');
    if (btnAddFaq) {
        btnAddFaq.addEventListener('click', function() {
            const modal = document.getElementById('modal-add-faq');
            if (modal) {
                modal.classList.remove('hidden');
            }
        });
    }
    
    // Edit FAQ chevron buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-faq-btn')) {
            const faqItem = e.target.closest('.faq-item');
            const faqData = {
                id: faqItem.dataset.id,
                question: faqItem.dataset.question,
                answer: faqItem.dataset.answer
            };
            
            // Call global function from modal-edit-faq.php
            if (typeof openEditFaqModal === 'function') {
                openEditFaqModal(faqData);
            }
        }
    });
});
</script>
