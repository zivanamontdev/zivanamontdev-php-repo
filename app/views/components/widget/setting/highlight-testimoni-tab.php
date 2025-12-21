<?php
/**
 * Highlight Testimoni Tab Widget
 * Widget untuk tab highlight testimoni
 */

// Fetch testimonial data from database
try {
    $testimonialModel = new Testimonial();
    $highlightTestimonials = $testimonialModel->getAll();
} catch (Exception $e) {
    error_log("Error loading testimonials: " . $e->getMessage());
    $highlightTestimonials = [];
}
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

<!-- Modal Edit Testimoni -->
<?php require VIEW_PATH . '/components/widget/setting/modal/modal-edit-testimoni.php'; ?>

<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Card Header -->
    <div class="mb-6">
        <div>
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Highlight Testimoni</h2>
            <p class="font-normal text-[14px] leading-[21px] text-black-soft">Kelola testimoni yang ditampilkan di halaman utama (maksimal 3 testimoni)</p>
        </div>
    </div>

    <!-- Sortable Testimonial List -->
    <div id="sortable-testimonials" class="space-y-[12px]">
        <?php if (empty($highlightTestimonials)): ?>
            <div class="text-center py-8 text-white-soft">
                <p>Belum ada testimoni yang ditambahkan (maksimal 3)</p>
            </div>
        <?php else: ?>
            <?php foreach ($highlightTestimonials as $testimonial): ?>
                <div class="testimonial-item rounded-[12px] py-[12px] px-[14px] flex items-center justify-between cursor-pointer transition-all" 
                     data-id="<?= $testimonial['id'] ?>" 
                     data-parent-name="<?= e($testimonial['parent_name']) ?>"
                     data-child-name="<?= e($testimonial['child_name']) ?>"
                     data-testimonial="<?= e($testimonial['testimonial_text']) ?>"
                     data-highlight="<?= e($testimonial['highlight_text']) ?>"
                     data-image="<?= !empty($testimonial['image']) ? '/uploads/testimonials/' . e($testimonial['image']) : '' ?>"
                     style="background-color: <?= colors('card_bg_light') ?>">
                    <!-- Left Section: Drag Handle + Content -->
                    <div class="flex items-center flex-1 min-w-0">
                        <!-- Drag Handle (6 dots icon) -->
                        <svg class="drag-handle w-[20px] h-[20px] text-white-soft cursor-move mr-[20px] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="7" cy="7" r="1.5"/>
                            <circle cx="7" cy="12" r="1.5"/>
                            <circle cx="7" cy="17" r="1.5"/>
                            <circle cx="12" cy="7" r="1.5"/>
                            <circle cx="12" cy="12" r="1.5"/>
                            <circle cx="12" cy="17" r="1.5"/>
                        </svg>
                        
                        <!-- Name + Testimonial Text -->
                        <div class="flex items-center min-w-0 flex-1" style="margin-right: 12px;">
                            <span class="font-bold text-[12px] text-black-neutral flex-shrink-0" style="margin-right: 8px;"><?= e($testimonial['parent_name']) ?></span>
                            <span class="font-normal text-[12px] text-text-dark truncate">"<?= e($testimonial['testimonial_text']) ?>"</span>
                        </div>
                    </div>
                    
                    <!-- Right Section: Chevron Right -->
                    <svg class="w-[16px] h-[16px] text-black-highlight flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Sortable for drag & drop
    const sortableTestimonials = document.getElementById('sortable-testimonials');
    const sortable = Sortable.create(sortableTestimonials, {
        animation: 150,
        handle: '.drag-handle',
        onEnd: function() {
            // Auto save order on drag
            updateTestimonialOrder();
        }
    });
    
    // Update testimonial order
    function updateTestimonialOrder() {
        const orders = {};
        const items = sortableTestimonials.querySelectorAll('.testimonial-item');
        items.forEach((item, index) => {
            orders[item.dataset.id] = index + 1;
        });
        
        // Send AJAX request to update order
        fetch('/admin/settings/testimonials/update-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'orders=' + encodeURIComponent(JSON.stringify(orders))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Urutan testimoni berhasil diperbarui', 'success', 2000);
            } else {
                console.error('Failed to update testimonial order:', data.message);
                showToast('Gagal mengupdate urutan: ' + data.message, 'error', 5000);
            }
        })
        .catch(error => {
            console.error('Error updating testimonial order:', error);
            showToast('Terjadi kesalahan saat mengupdate urutan', 'error', 5000);
        });
    }
    
    // Click on testimonial item
    document.addEventListener('click', function(e) {
        const testimonialItem = e.target.closest('.testimonial-item');
        if (testimonialItem && !e.target.closest('.drag-handle')) {
            // Get data from data attributes
            const testimonialData = {
                id: testimonialItem.dataset.id,
                parentName: testimonialItem.dataset.parentName,
                childName: testimonialItem.dataset.childName,
                testimonial: testimonialItem.dataset.testimonial,
                highlight: testimonialItem.dataset.highlight,
                image: testimonialItem.dataset.image
            };
            
            // Open modal with testimonial data
            if (typeof openEditTestimoniModal === 'function') {
                openEditTestimoniModal(testimonialData);
            }
        }
    });
});
</script>
