<?php
/**
 * Highlight Program Sekolah Tab Widget
 * Widget untuk tab highlight program sekolah
 */

// Fetch highlight programs from database
try {
    $highlightProgramModel = new HighlightProgram();
    $highlightPrograms = $highlightProgramModel->getAll();
} catch (Exception $e) {
    error_log("Error loading highlight programs: " . $e->getMessage());
    $highlightPrograms = [];
}
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

<!-- Modal Ganti Highlight Program -->
<?php require VIEW_PATH . '/components/widget/setting/modal/modal-ganti-highlight-program.php'; ?>

<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Card Header -->
    <div class="mb-6">
        <div>
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Highlight Program Sekolah</h2>
            <p class="font-normal text-[14px] leading-[21px] text-black-soft">Kelola program unggulan yang ditampilkan di halaman utama (maksimal 3)</p>
        </div>
    </div>

    <!-- Sortable Program List -->
    <div id="sortable-programs" class="space-y-[12px]">
        <?php if (empty($highlightPrograms)): ?>
            <div class="text-center py-8 text-white-soft">
                <p>Belum ada program yang di-highlight (maksimal 3)</p>
            </div>
        <?php else: ?>
            <?php foreach ($highlightPrograms as $program): ?>
                <div class="program-item rounded-[12px] py-[12px] px-[14px] flex items-center justify-between cursor-pointer transition-all" 
                     data-id="<?= $program['id'] ?>"
                     data-program-id="<?= $program['program_tahun_id'] ?>"
                     data-program-name="<?= e($program['name']) ?>"
                     style="background-color: <?= colors('card_bg_light') ?>">
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
                        <h3 class="font-bold text-[12px] text-black-neutral"><?= e($program['name']) ?></h3>
                    </div>
                    
                    <!-- Right Section: Chevron Right -->
                    <svg class="w-[16px] h-[16px] text-black-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        
        // Send AJAX request to update order
        fetch('/admin/settings/highlight-programs/update-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'orders=' + encodeURIComponent(JSON.stringify(orders))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Urutan highlight program berhasil diperbarui', 'success', 2000);
            } else {
                console.error('Failed to update order:', data.message);
                showToast('Gagal mengupdate urutan: ' + data.message, 'error', 5000);
            }
        })
        .catch(error => {
            console.error('Error updating order:', error);
            showToast('Terjadi kesalahan saat mengupdate urutan', 'error', 5000);
        });
    }
    
    // Click on program item
    document.addEventListener('click', function(e) {
        const programItem = e.target.closest('.program-item');
        if (programItem && !e.target.closest('.drag-handle')) {
            const highlightId = programItem.dataset.id;
            // Open modal to change highlight program
            if (typeof openModalGantiHighlightProgram === 'function') {
                openModalGantiHighlightProgram(highlightId);
            }
        }
    });
});
</script>
