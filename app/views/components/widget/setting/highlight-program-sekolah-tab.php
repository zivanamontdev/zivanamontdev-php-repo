<?php
/**
 * Highlight Program Sekolah Tab Widget
 * Widget untuk tab highlight program sekolah
 */

// Dummy data for preview (will be replaced with actual data from controller)
$highlightPrograms = [
    [
        'id' => 1,
        'program_name' => 'Montessori Learning Method',
        'order_index' => 1
    ],
    [
        'id' => 2,
        'program_name' => 'Bilingual Education Program',
        'order_index' => 2
    ],
    [
        'id' => 3,
        'program_name' => 'Character Building Activities',
        'order_index' => 3
    ]
];
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

<!-- Modal Ganti Highlight Program -->
<?php require VIEW_PATH . '/components/widget/setting/modal/modal-ganti-highlight-program.php'; ?>

<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Card Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Highlight Program Sekolah</h2>
            <p class="font-normal text-[14px] leading-[21px] text-black-soft">Kelola program unggulan yang ditampilkan di halaman utama</p>
        </div>
        
        <!-- Add Button -->
        <?php component('button', [
            'text' => 'Tambah Program',
            'variant' => '8',
            'type' => 'button',
            'icon' => 'plus',
            'id' => 'btn-add-program'
        ]); ?>
    </div>

    <!-- Sortable Program List -->
    <div id="sortable-programs" class="space-y-[12px]">
        <?php foreach ($highlightPrograms as $program): ?>
            <div class="program-item rounded-[12px] py-[12px] px-[14px] flex items-center justify-between cursor-pointer transition-all" data-id="<?= $program['id'] ?>" style="background-color: <?= colors('card_bg_light') ?>">
                <!-- Left Section: Drag Handle + Title -->
                <div class="flex items-center">
                    <!-- Drag Handle (6 dots icon) -->
                    <svg class="drag-handle w-[20px] h-[20px] text-white-soft cursor-move mr-[20px]" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="7" cy="7" r="1.5"/>
                        <circle cx="7" cy="12" r="1.5"/>
                        <circle cx="7" cy="17" r="1.5"/>
                        <circle cx="12" cy="7" r="1.5"/>
                        <circle cx="12" cy="12" r="1.5"/>
                        <circle cx="12" cy="17" r="1.5"/>
                    </svg>
                    
                    <!-- Program Title -->
                    <h3 class="font-bold text-[12px] text-black-neutral"><?= e($program['program_name']) ?></h3>
                </div>
                
                <!-- Right Section: Chevron Right -->
                <svg class="w-[16px] h-[16px] text-black-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    const sortablePrograms = document.getElementById('sortable-programs');
    const sortable = Sortable.create(sortablePrograms, {
        animation: 150,
        handle: '.drag-handle',
        onEnd: function() {
            // Auto save order on drag
            updateProgramOrder();
        }
    });
    
    // Update program order
    function updateProgramOrder() {
        const orders = {};
        const items = sortablePrograms.querySelectorAll('.program-item');
        items.forEach((item, index) => {
            orders[item.dataset.id] = index + 1;
        });
        
        const formData = new FormData();
        formData.append('orders', JSON.stringify(orders));
        
        // TODO: Implement API endpoint for updating order
        console.log('Update order:', orders);
        
        // fetch('<?= url('/admin/settings/highlight-programs/update-order') ?>', {
        //     method: 'POST',
        //     body: formData
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         showToast('Urutan program berhasil diupdate', 'success');
        //     } else {
        //         showToast('Gagal mengupdate urutan: ' + data.message, 'error');
        //     }
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     showToast('Terjadi kesalahan', 'error');
        // });
    }
    
    // Add program button
    document.getElementById('btn-add-program').addEventListener('click', function() {
        showToast('Fitur tambah program akan segera tersedia', 'info');
        // TODO: Implement add program modal
    });
    
    // Click on program item
    document.addEventListener('click', function(e) {
        const programItem = e.target.closest('.program-item');
        if (programItem && !e.target.closest('.drag-handle')) {
            const programId = programItem.dataset.id;
            // Open modal to change highlight program
            openModalGantiHighlightProgram(programId);
        }
    });
});
</script>
