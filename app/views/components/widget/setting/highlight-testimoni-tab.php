<?php
/**
 * Highlight Testimoni Tab Widget
 * Widget untuk tab highlight testimoni
 */

// Dummy data for preview (will be replaced with actual data from controller)
$highlightTestimonials = [
    [
        'id' => 1,
        'name' => 'Ibu Sarah Wijaya',
        'child_name' => 'Alya Putri',
        'testimonial' => 'Anak saya sangat senang belajar di sini. Metode pengajarannya sangat baik dan guru-gurunya sangat perhatian terhadap perkembangan anak.',
        'highlight' => 'Metode pengajaran sangat baik',
        'image' => '',
        'order_index' => 1
    ],
    [
        'id' => 2,
        'name' => 'Bapak Ahmad Hidayat',
        'child_name' => 'Faris Ahmad',
        'testimonial' => 'Fasilitas yang lengkap dan lingkungan yang nyaman membuat anak-anak betah belajar. Perkembangan anak saya sangat pesat sejak bersekolah di sini.',
        'highlight' => 'Perkembangan anak sangat pesat',
        'image' => '',
        'order_index' => 2
    ],
    [
        'id' => 3,
        'name' => 'Ibu Kartika Sari',
        'child_name' => 'Nadira Sari',
        'testimonial' => 'Sangat puas dengan program pembelajaran yang ditawarkan. Anak saya menjadi lebih mandiri dan percaya diri setelah bersekolah di sini.',
        'highlight' => 'Anak menjadi lebih mandiri',
        'image' => '',
        'order_index' => 3
    ]
];
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

<!-- Modal Edit Testimoni -->
<?php require VIEW_PATH . '/components/widget/setting/modal/modal-edit-testimoni.php'; ?>

<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Card Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Highlight Testimoni</h2>
            <p class="font-normal text-[14px] leading-[21px] text-black-soft">Kelola testimoni yang ditampilkan di halaman utama</p>
        </div>
        
        <!-- Add Button -->
        <?php component('button', [
            'text' => 'Tambah Testimoni',
            'variant' => '8',
            'type' => 'button',
            'icon' => 'plus',
            'id' => 'btn-add-testimonial'
        ]); ?>
    </div>

    <!-- Sortable Testimonial List -->
    <div id="sortable-testimonials" class="space-y-[12px]">
        <?php foreach ($highlightTestimonials as $testimonial): ?>
            <div class="testimonial-item rounded-[12px] py-[12px] px-[14px] flex items-center justify-between cursor-pointer transition-all" data-id="<?= $testimonial['id'] ?>" style="background-color: <?= colors('card_bg_light') ?>">
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
                        <span class="font-bold text-[12px] text-black-neutral flex-shrink-0" style="margin-right: 8px;"><?= e($testimonial['name']) ?></span>
                        <span class="font-normal text-[12px] text-text-dark truncate">"<?= e($testimonial['testimonial']) ?>"</span>
                    </div>
                </div>
                
                <!-- Right Section: Chevron Right -->
                <svg class="w-[16px] h-[16px] text-black-highlight flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

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
        
        const formData = new FormData();
        formData.append('orders', JSON.stringify(orders));
        
        // TODO: Implement API endpoint for updating order
        console.log('Update order:', orders);
        
        // fetch('<?= url('/admin/settings/highlight-testimonials/update-order') ?>', {
        //     method: 'POST',
        //     body: formData
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         showToast('Urutan testimoni berhasil diupdate', 'success');
        //     } else {
        //         showToast('Gagal mengupdate urutan: ' + data.message, 'error');
        //     }
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     showToast('Terjadi kesalahan', 'error');
        // });
    }
    
    // Add testimonial button
    document.getElementById('btn-add-testimonial').addEventListener('click', function() {
        showToast('Fitur tambah testimoni akan segera tersedia', 'info');
        // TODO: Implement add testimonial modal
    });
    
    // Click on testimonial item
    document.addEventListener('click', function(e) {
        const testimonialItem = e.target.closest('.testimonial-item');
        if (testimonialItem && !e.target.closest('.drag-handle')) {
            const testimonialId = testimonialItem.dataset.id;
            
            // Get testimonial data from PHP
            const testimonialsData = <?= json_encode($highlightTestimonials) ?>;
            const testimonial = testimonialsData.find(t => t.id == testimonialId);
            
            if (testimonial) {
                // Open modal with testimonial data
                openEditTestimoniModal({
                    id: testimonial.id,
                    parentName: testimonial.name,
                    childName: testimonial.child_name,
                    testimonial: testimonial.testimonial,
                    highlight: testimonial.highlight,
                    image: testimonial.image
                });
            }
        }
    });
});
</script>
