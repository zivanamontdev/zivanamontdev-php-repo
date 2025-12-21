<?php
/**
 * Modal Ganti Highlight Program
 * Modal untuk mengganti program highlight
 */

// This will be fetched via AJAX when modal opens
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
            <!-- Will be populated via AJAX -->
            <div class="text-center py-8 text-white-soft">
                <p>Memuat data program...</p>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-ganti-highlight-program');
    const closeBtn = document.getElementById('close-modal-highlight-program');
    const searchInput = document.getElementById('search-program-input');
    const programsList = document.getElementById('programs-list');
    let currentHighlightId = null;
    let allPrograms = [];

    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
        // Clear search
        if (searchInput) {
            searchInput.value = '';
        }
        currentHighlightId = null;
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
    
    // Load programs from server
    function loadPrograms() {
        programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Memuat data program...</p></div>';
        
        fetch('/admin/settings/highlight-programs/available')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    allPrograms = data.data;
                    renderPrograms(allPrograms);
                } else {
                    programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Gagal memuat data program</p></div>';
                }
            })
            .catch(error => {
                console.error('Error loading programs:', error);
                programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Terjadi kesalahan saat memuat data</p></div>';
            });
    }
    
    // Render programs
    function renderPrograms(programs) {
        if (programs.length === 0) {
            programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Tidak ada program tersedia</p></div>';
            return;
        }
        
        programsList.innerHTML = '';
        programs.forEach(program => {
            const programTile = createProgramTile(program);
            programsList.appendChild(programTile);
        });
    }
    
    // Create program tile element
    function createProgramTile(program) {
        const div = document.createElement('div');
        div.className = 'program-tile flex items-center justify-between cursor-pointer hover:opacity-70 transition-opacity';
        div.dataset.programId = program.id;
        div.dataset.programTitle = program.name;
        
        const imagePath = program.image ? (program.image.startsWith('/') ? program.image : '/uploads/programs-tahun/' + program.image) : '';
        
        div.innerHTML = `
            <!-- Left Section: Image + Text -->
            <div class="flex items-center flex-1">
                <!-- Program Image / Placeholder -->
                <div class="flex-shrink-0 mr-[12px]" style="width: 62px; height: 62px; border-radius: 8px; background-color: <?= colors('gray_placeholder') ?>; overflow: hidden;">
                    ${imagePath ? `
                        <img src="${imagePath}" alt="${program.name}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                    ` : `
                        <div class="w-full h-full flex items-center justify-center">
                    `}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 16V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H5C4.46957 6 3.96086 6.21071 3.58579 6.58579C3.21071 6.96086 3 7.46957 3 8V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H19C19.5304 18 20.0391 17.7893 20.4142 17.4142C20.7893 17.0391 21 16.5304 21 16Z" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 12.5L7 9L13 14L21 6" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                </div>

                <!-- Program Info -->
                <div class="flex flex-col justify-center">
                    <h3 class="font-bold text-[12px] text-black-soft mb-[2px]">${program.name}</h3>
                    <p class="font-normal text-[12px] text-black-highlight">${program.description || ''}</p>
                </div>
            </div>

            <!-- Right Section: Chevron Icon -->
            <div class="flex-shrink-0 ml-[12px]">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12L10 8L6 4" stroke="<?= colors('black_highlight') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        `;
        
        // Add click handler
        div.addEventListener('click', function() {
            handleProgramSelect(program.id, program.name);
        });
        
        return div;
    }

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredPrograms = allPrograms.filter(program => 
                program.name.toLowerCase().includes(searchTerm) ||
                (program.description && program.description.toLowerCase().includes(searchTerm))
            );
            renderPrograms(filteredPrograms);
        });
    }

    // Handle program selection
    function handleProgramSelect(programId, programName) {
        if (!currentHighlightId) {
            showToast('Highlight ID tidak valid', 'error', 3000);
            return;
        }
        
        // Send AJAX to replace highlight
        const formData = new FormData();
        formData.append('program_id', programId);
        
        fetch(`/admin/settings/highlight-programs/${currentHighlightId}/replace`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Program berhasil diganti!', 'success', 3000);
                closeModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Gagal mengganti program', 'error', 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengganti program', 'error', 5000);
        });
    }
});

// Global function to open modal (called from parent component)
function openModalGantiHighlightProgram(highlightId) {
    const modal = document.getElementById('modal-ganti-highlight-program');
    if (modal) {
        // Store current highlight ID
        window.currentHighlightId = highlightId;
        
        // Load programs
        const programsList = document.getElementById('programs-list');
        programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Memuat data program...</p></div>';
        
        fetch('/admin/settings/highlight-programs/available')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const programs = data.data;
                    
                    if (programs.length === 0) {
                        programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Tidak ada program tersedia</p></div>';
                        return;
                    }
                    
                    programsList.innerHTML = '';
                    programs.forEach(program => {
                        const div = document.createElement('div');
                        div.className = 'program-tile flex items-center justify-between cursor-pointer hover:opacity-70 transition-opacity';
                        div.dataset.programId = program.id;
                        
                        const imagePath = program.image ? (program.image.startsWith('/') ? program.image : '/uploads/programs-tahun/' + program.image) : '';
                        
                        div.innerHTML = `
                            <div class="flex items-center flex-1">
                                <div class="flex-shrink-0 mr-[12px]" style="width: 62px; height: 62px; border-radius: 8px; background-color: <?= colors('gray_placeholder') ?>; overflow: hidden;">
                                    ${imagePath ? `
                                        <img src="${imagePath}" alt="${program.name}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                    ` : `
                                        <div class="w-full h-full flex items-center justify-center">
                                    `}
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 16V8C21 7.46957 20.7893 6.96086 20.4142 6.58579C20.0391 6.21071 19.5304 6 19 6H5C4.46957 6 3.96086 6.21071 3.58579 6.58579C3.21071 6.96086 3 7.46957 3 8V16C3 16.5304 3.21071 17.0391 3.58579 17.4142C3.96086 17.7893 4.46957 18 5 18H19C19.5304 18 20.0391 17.7893 20.4142 17.4142C20.7893 17.0391 21 16.5304 21 16Z" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3 12.5L7 9L13 14L21 6" stroke="<?= colors('white_soft') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h3 class="font-bold text-[12px] text-black-soft mb-[2px]">${program.name}</h3>
                                    <p class="font-normal text-[12px] text-black-highlight">${program.description || ''}</p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-[12px]">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 12L10 8L6 4" stroke="<?= colors('black_highlight') ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        `;
                        
                        div.addEventListener('click', function() {
                            const formData = new FormData();
                            formData.append('program_id', program.id);
                            
                            fetch(`/admin/settings/highlight-programs/${highlightId}/replace`, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    modal.classList.add('hidden');
                                    showToast('Program berhasil diganti!', 'success', 3000);
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    showToast(data.message || 'Gagal mengganti program', 'error', 5000);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan', 'error', 5000);
                            });
                        });
                        
                        programsList.appendChild(div);
                    });
                } else {
                    programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Gagal memuat data program</p></div>';
                }
            })
            .catch(error => {
                console.error('Error loading programs:', error);
                programsList.innerHTML = '<div class="text-center py-8 text-white-soft"><p>Terjadi kesalahan</p></div>';
            });
        
        modal.classList.remove('hidden');
    }
}
</script>
