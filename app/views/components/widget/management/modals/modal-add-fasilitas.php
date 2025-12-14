<?php
/**
 * Modal Add Fasilitas Component
 * Modal untuk menambahkan fasilitas baru
 */
?>

<!-- Modal Backdrop -->
<div id="modal-add-fasilitas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Fasilitas Sekolah Baru</h3>
            <button type="button" id="close-modal-add-fasilitas" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Image Upload Section -->
        <div class="flex items-start gap-[20px] mb-[24px] justify-center">
            <!-- Image Preview Container with Caution -->
            <div class="flex flex-col items-center gap-[8px]">
                <div class="w-[124px] h-[124px] bg-white-secondary border-2 border-dashed border-border-light rounded-[16px] flex items-center justify-center overflow-hidden flex-shrink-0 relative">
                    <img id="preview-image-fasilitas-add" src="" alt="Preview" class="w-full h-full object-cover absolute inset-0 hidden">
                    <svg id="placeholder-icon-fasilitas-add" class="w-[48px] h-[48px] text-white-shadow" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p id="caution-text-add-fasilitas" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="fasilitas-image-input-add" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Foto',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="fasilitas-image-input-add" name="image" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-fasilitas-add">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-fasilitas-add'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-add-fasilitas" class="space-y-[24px]">
            <!-- Nama Fasilitas -->
            <div>
                <label for="fasilitas-name-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Fasilitas
                </label>
                <input 
                    type="text" 
                    id="fasilitas-name-add" 
                    name="fasilitas_name"
                    placeholder="Isi nama fasilitas"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Action Button -->
            <div class="pt-[8px] flex items-center justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-fasilitas-add">
                    <?php component('button', [
                        'text' => 'Tambah Fasilitas Baru',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-add-fasilitas-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Delete image function (global)
window.deleteImageFasilitasAdd = function() {
    const imageInput = document.getElementById('fasilitas-image-input-add');
    const previewImage = document.getElementById('preview-image-fasilitas-add');
    const placeholderIcon = document.getElementById('placeholder-icon-fasilitas-add');
    const cautionText = document.getElementById('caution-text-add-fasilitas');
    
    if (imageInput) imageInput.value = '';
    if (previewImage) {
        previewImage.src = '';
        previewImage.classList.add('hidden');
    }
    if (placeholderIcon) placeholderIcon.classList.remove('hidden');
    
    if (cautionText) {
        cautionText.textContent = 'Maksimal ukuran file 2MB';
        cautionText.style.color = '<?= colors("white_shadow") ?>';
    }
    
    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-add');
    if (deleteButtonWrapper) {
        deleteButtonWrapper.innerHTML = `
            <button type="button" id="delete-image-btn-fasilitas-add" disabled class="btn-component inline-flex items-center justify-center transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                Hapus Gambar
            </button>
        `;
    }
};

// Function to open add modal
window.openAddFasilitasModal = function() {
    const modal = document.getElementById('modal-add-fasilitas');
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-fasilitas');
    const closeBtn = document.getElementById('close-modal-add-fasilitas');
    const imageInput = document.getElementById('fasilitas-image-input-add');
    const previewImage = document.getElementById('preview-image-fasilitas-add');
    const placeholderIcon = document.getElementById('placeholder-icon-fasilitas-add');
    const deleteImageBtn = document.getElementById('delete-image-btn-fasilitas-add');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetAddFasilitasForm();
        });
    }
    
    // Image upload handler
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-add-fasilitas');
            const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-add');
            
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    cautionText.textContent = 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP';
                    cautionText.style.color = '<?= colors("red_error") ?>';
                    imageInput.value = '';
                    return;
                }
                
                // Validate file size (2MB)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    cautionText.textContent = 'Ukuran file terlalu besar. Maksimal 2MB';
                    cautionText.style.color = '<?= colors("red_error") ?>';
                    imageInput.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                    previewImage.classList.remove('hidden');
                    placeholderIcon.classList.add('hidden');
                    
                    // Reset caution text
                    cautionText.textContent = 'Maksimal ukuran file 2MB';
                    cautionText.style.color = '<?= colors("white_shadow") ?>';
                    
                    // Enable delete button (variant 1)
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-fasilitas-add" class="btn-component w-full inline-flex justify-center items-center py-[8px] px-[16px] border border-solid rounded-[4px] font-normal text-base leading-[28px]
                            bg-<?= colors("red_error") ?> text-<?= colors("white_neutral") ?> border-<?= colors("red_error") ?> 
                            hover:bg-<?= colors("red_error_hover") ?> hover:border-<?= colors("red_error_hover") ?> 
                            transition-colors duration-300">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-fasilitas-add');
                        if (newDeleteBtn) {
                            newDeleteBtn.addEventListener('click', window.deleteImageFasilitasAdd);
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Delete button initial binding
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', window.deleteImageFasilitasAdd);
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetAddFasilitasForm();
        }
    });
    
    // Form validation
    function validateAddFasilitasForm() {
        const fasilitasName = document.getElementById('fasilitas-name-add').value.trim();
        
        const allFilled = fasilitasName;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-fasilitas-add');
        
        if (allFilled) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-fasilitas-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-primary text-white hover:bg-primary-dark active:scale-95 cursor-pointer h-[52px]">
                    Tambah Fasilitas Baru
                </button>
            `;
        } else {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Fasilitas Baru
                </button>
            `;
        }
    }
    
    window.validateAddFasilitasForm = validateAddFasilitasForm;
    
    const input = document.getElementById('fasilitas-name-add');
    if (input) {
        input.addEventListener('input', validateAddFasilitasForm);
    }
    
    // Form submit
    const form = document.getElementById('form-add-fasilitas');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            localStorage.setItem('activeManagementTabIndex', '2');
            
            const formData = new FormData();
            formData.append('name', document.getElementById('fasilitas-name-add').value);
            
            // Append image if exists
            const imageInput = document.getElementById('fasilitas-image-input-add');
            if (imageInput && imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }
            
            fetch(`/admin/management/fasilitas/create`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetAddFasilitasForm();
                    showToast('Fasilitas berhasil ditambahkan!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat menambah data', 'error', 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menambah data', 'error', 5000);
            });
        });
    }
    
    // Reset form
    function resetAddFasilitasForm() {
        if (form) form.reset();
        
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-fasilitas-add');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Fasilitas Baru
                </button>
            `;
        }
    }
});
</script>
