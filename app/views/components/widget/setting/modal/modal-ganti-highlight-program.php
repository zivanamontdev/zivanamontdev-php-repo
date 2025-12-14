<?php
/**
 * Modal Ganti Highlight Program
 * Modal untuk mengganti program highlight
 */

// Dummy data programs
$programs = [
    [
        'id' => 1,
        'title' => 'Montessori Learning Method',
        'description' => 'Program pendidikan berbasis metode montessori',
        'image' => '' // Empty for placeholder
    ],
    [
        'id' => 2,
        'title' => 'Bilingual Education Program',
        'description' => 'Program pembelajaran dua bahasa',
        'image' => '' // Empty for placeholder
    ],
    [
        'id' => 3,
        'title' => 'Character Building Activities',
        'description' => 'Kegiatan pembentukan karakter anak',
        'image' => '' // Empty for placeholder
    ],
    [
        'id' => 4,
        'title' => 'Creative Arts & Music',
        'description' => 'Program seni dan musik kreatif',
        'image' => '' // Empty for placeholder
    ],
    [
        'id' => 5,
        'title' => 'Science & Technology',
        'description' => 'Program sains dan teknologi',
        'image' => '' // Empty for placeholder
    ]
];
?>

<!-- Modal Overlay -->
<div id="modal-ganti-highlight-program" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background-color: rgba(0, 0, 0, 0.5);">
    <!-- Modal Container -->
    <div class="relative" style="width: 621px; background-color: <?= colors('white_neutral') ?>; border: 1px solid <?= colors('border_soft') ?>; border-radius: 16px; padding: 20px 24px;">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-[32px]">
            <h2 class="font-bold text-[20px] text-black-soft">Ganti Highlight Program</h2>
            
            <!-- Close Button -->
            <button type="button" id="close-modal-highlight-program" class="p-1 hover:opacity-70 transition-opacity">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="<?= colors('black_highlight') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <!-- Search Input -->
        <div class="mb-[24px]">
            <?php component('input', [
                'name' => 'search_program',
                'type' => 'text',
                'placeholder' => 'Cari Program',
                'icon' => 'search',
                'id' => 'search-program-input'
            ]); ?>
        </div>

        <!-- Programs List -->
        <div id="programs-list" class="space-y-[16px] max-h-[400px] overflow-y-auto">
            <?php foreach ($programs as $program): ?>
                <div class="program-tile flex items-center justify-between cursor-pointer hover:opacity-70 transition-opacity" data-program-id="<?= $program['id'] ?>" data-program-title="<?= e($program['title']) ?>">
                    <!-- Left Section: Image + Text -->
                    <div class="flex items-center flex-1">
                        <!-- Program Image / Placeholder -->
                        <div class="flex-shrink-0 mr-[12px]" style="width: 62px; height: 62px; border-radius: 8px; background-color: <?= colors('gray_placeholder') ?>; overflow: hidden;">
                            <?php if (!empty($program['image'])): ?>
                                <img src="<?= e($program['image']) ?>" alt="<?= e($program['title']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <!-- Placeholder Icon -->
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 16V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H5C4.46957 6 3.96086 6.21071 3.58579 6.58579C3.21071 6.96086 3 7.46957 3 8V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H19C19.5304 18 20.0391 17.7893 20.4142 17.4142C20.7893 17.0391 21 16.5304 21 16Z" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M3 12.5L7 9L13 14L21 6" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Program Info -->
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-[12px] text-black-soft mb-[2px]"><?= e($program['title']) ?></h3>
                            <p class="font-normal text-[12px] text-black-highlight"><?= e($program['description']) ?></p>
                        </div>
                    </div>

                    <!-- Right Section: Chevron Icon -->
                    <div class="flex-shrink-0 ml-[12px]">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 12L10 8L6 4" stroke="<?= colors('black_highlight') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-ganti-highlight-program');
    const closeBtn = document.getElementById('close-modal-highlight-program');
    const searchInput = document.getElementById('search-program-input');
    const programTiles = document.querySelectorAll('.program-tile');

    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
        // Clear search
        if (searchInput) {
            searchInput.value = '';
            // Show all programs
            programTiles.forEach(tile => {
                tile.style.display = 'flex';
            });
        }
    }

    // Close button click
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            programTiles.forEach(tile => {
                const title = tile.dataset.programTitle.toLowerCase();
                if (title.includes(searchTerm)) {
                    tile.style.display = 'flex';
                } else {
                    tile.style.display = 'none';
                }
            });
        });
    }

    // Program tile click
    programTiles.forEach(tile => {
        tile.addEventListener('click', function() {
            const programId = this.dataset.programId;
            const programTitle = this.dataset.programTitle;
            
            console.log('Selected program:', programId, programTitle);
            
            // TODO: Implement API call to update highlight program
            showToast('Program berhasil dipilih: ' + programTitle, 'success');
            
            // Close modal
            closeModal();
        });
    });
});

// Global function to open modal (called from parent component)
function openModalGantiHighlightProgram(highlightId) {
    const modal = document.getElementById('modal-ganti-highlight-program');
    if (modal) {
        modal.classList.remove('hidden');
        console.log('Opening modal for highlight ID:', highlightId);
    }
}
</script>
