<?php
/**
 * Modal Edit Fasilitas Component
 * Modal untuk mengedit fasilitas
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-fasilitas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Informasi Fasilitas</h3>
            <button type="button" id="close-modal-edit-fasilitas" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Image Upload Section -->
        <div class="flex items-start gap-[20px] mb-[24px] justify-center">
            <!-- Image Preview Container with Caution -->
            <div class="flex flex-col items-center gap-[8px]">
                <div class="w-[124px] h-[124px] rounded-[14px] bg-gray-placeholder flex items-center justify-center flex-shrink-0 relative">
                    <img id="preview-image-fasilitas-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-fasilitas-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-edit-fasilitas" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="fasilitas-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="fasilitas-image-input-edit" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-fasilitas-edit">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-fasilitas-edit'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-fasilitas" class="space-y-[24px]">
            <!-- Hidden field for fasilitas ID -->
            <input type="hidden" id="fasilitas-id-edit" name="fasilitas_id">
            
            <!-- Nama Fasilitas -->
            <div>
                <label for="fasilitas-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Fasilitas
                </label>
                <input 
                    type="text" 
                    id="fasilitas-name-edit" 
                    name="fasilitas_name"
                    placeholder="Isi nama fasilitas"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center gap-[12px]" style="margin-top: 32px;">
                <!-- Delete Button -->
                <div id="delete-fasilitas-button-wrapper">
                    <?php component('button', [
                        'text' => 'Hapus Fasilitas',
                        'variant' => '13',
                        'type' => 'button',
                        'id' => 'delete-fasilitas-btn',
                        'icon' => 'trash',
                        'iconPosition' => 'left'
                    ]); ?>
                </div>
                
                <!-- Submit Button -->
                <div id="submit-button-wrapper-fasilitas-edit">
                    <?php component('button', [
                        'text' => 'Ubah Informasi Fasilitas',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-edit-fasilitas-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Global function for deleting image preview in edit modal
window.deleteImageFasilitasEdit = function() {
    const imageInput = document.getElementById('fasilitas-image-input-edit');
    const previewImage = document.getElementById('preview-image-fasilitas-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-fasilitas-edit');
    const cautionText = document.getElementById('caution-text-edit-fasilitas');
    
    // Clear input
    imageInput.value = '';
    previewImage.src = '';
    previewImage.classList.add('hidden');
    placeholderIcon.classList.remove('hidden');
    
    // Reset caution text
    if (cautionText) {
        cautionText.textContent = 'Maksimal ukuran file 2MB';
        cautionText.style.color = '<?= colors("white_shadow") ?>';
    }
    
    // Change delete button back to variant 10
    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-edit');
    if (deleteButtonWrapper) {
        deleteButtonWrapper.innerHTML = `
            <button type="button" id="delete-image-btn-fasilitas-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                Hapus Gambar
            </button>
        `;
        
        // Re-attach event listener
        const newDeleteBtn = document.getElementById('delete-image-btn-fasilitas-edit');
        if (newDeleteBtn) {
            newDeleteBtn.addEventListener('click', window.deleteImageFasilitasEdit);
        }
    }
};

// Function to open edit modal
function openEditFasilitasModal(fasilitasData) {
    const modal = document.getElementById('modal-edit-fasilitas');
    const previewImage = document.getElementById('preview-image-fasilitas-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-fasilitas-edit');
    
    // Fill form fields
    document.getElementById('fasilitas-id-edit').value = fasilitasData.id;
    document.getElementById('fasilitas-name-edit').value = fasilitasData.name;
    
    // Set image preview if exists
    if (fasilitasData.image) {
        previewImage.src = fasilitasData.image;
        previewImage.style.display = '';
        previewImage.classList.remove('hidden');
        placeholderIcon.classList.add('hidden');
        
        // Change delete button to variant 11
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-fasilitas-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener
            const newDeleteBtn = document.getElementById('delete-image-btn-fasilitas-edit');
            if (newDeleteBtn) {
                newDeleteBtn.addEventListener('click', window.deleteImageFasilitasEdit);
            }
        }
    }
    
    // Trigger form validation
    validateEditFasilitasForm();
    
    // Show modal
    modal.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-fasilitas');
    const closeBtn = document.getElementById('close-modal-edit-fasilitas');
    const deleteFasilitasBtn = document.getElementById('delete-fasilitas-btn');
    const imageInput = document.getElementById('fasilitas-image-input-edit');
    const previewImage = document.getElementById('preview-image-fasilitas-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-fasilitas-edit');
    const deleteImageBtn = document.getElementById('delete-image-btn-fasilitas-edit');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetEditFasilitasForm();
        });
    }
    
    // Image upload handler
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-edit-fasilitas');
            
            if (file) {
                // Validate file type before preview
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showToast('Tipe file tidak valid. Hanya JPG, PNG, GIF, dan WEBP yang diperbolehkan.', 'error', 3000);
                    imageInput.value = '';
                    return;
                }
                
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    if (cautionText) {
                        cautionText.textContent = 'File gambar melebihi 2MB';
                        cautionText.style.color = '<?= colors("primary") ?>';
                    }
                    return;
                } else {
                    if (cautionText) {
                        cautionText.textContent = 'Maksimal ukuran file 2MB';
                        cautionText.style.color = '<?= colors("white_shadow") ?>';
                    }
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage && placeholderIcon) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = '';
                        previewImage.classList.remove('hidden');
                        placeholderIcon.classList.add('hidden');
                    }
                    
                    // Change delete button to variant 11
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-edit');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-fasilitas-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-fasilitas-edit');
                        if (newDeleteBtn) {
                            newDeleteBtn.addEventListener('click', window.deleteImageFasilitasEdit);
                        }
                    }
                };
                reader.onerror = function() {
                    showToast('Gagal membaca file gambar', 'error', 3000);
                    imageInput.value = '';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Delete button initial binding
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', window.deleteImageFasilitasEdit);
    }
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetEditFasilitasForm();
        }
    });
    
    function validateEditFasilitasForm() {
        const fasilitasName = document.getElementById('fasilitas-name-edit').value.trim();
        
        const allFilled = fasilitasName;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-fasilitas-edit');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-fasilitas-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Informasi Fasilitas
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Fasilitas
                </button>
            `;
        }
    }
    
    // Make validateEditFasilitasForm available globally
    window.validateEditFasilitasForm = validateEditFasilitasForm;
    
    const input = document.getElementById('fasilitas-name-edit');
    if (input) {
        input.addEventListener('input', validateEditFasilitasForm);
    }
    
    const form = document.getElementById('form-edit-fasilitas');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store active tab before submit to prevent glitch on reload
            localStorage.setItem('activeManagementTabIndex', '2');
            
            const fasilitasId = document.getElementById('fasilitas-id-edit').value;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('name', document.getElementById('fasilitas-name-edit').value);
            
            // Add image if uploaded
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Send AJAX request
            fetch(`/admin/management/fasilitas/${fasilitasId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetEditFasilitasForm();
                    showToast('Fasilitas berhasil diubah!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat mengubah data', 'error', 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat mengubah data', 'error', 5000);
            });
        });
    }
    
    if (deleteFasilitasBtn) {
        deleteFasilitasBtn.addEventListener('click', function() {
            openModalDeleteFasilitas(
                'Hapus Fasilitas',
                'Apakah Anda yakin ingin menghapus fasilitas ini? Semua galeri foto akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.',
                function() {
                    // Store active tab before delete to prevent glitch on reload
                    localStorage.setItem('activeManagementTabIndex', '2');
                    
                    const fasilitasId = document.getElementById('fasilitas-id-edit').value;
                    
                    // Send AJAX request
                    fetch(`/admin/management/fasilitas/${fasilitasId}/delete`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modal.classList.add('hidden');
                            resetEditFasilitasForm();
                            showToast('Fasilitas berhasil dihapus!', 'success', 3000);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showToast(data.message || 'Terjadi kesalahan saat menghapus data', 'error', 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menghapus data', 'error', 5000);
                    });
                }
            );
        });
    }
    
    // Reset form
    function resetEditFasilitasForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        // Reset delete button to variant 10
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-fasilitas-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-fasilitas-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-fasilitas-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Fasilitas
                </button>
            `;
        }
    }
});
</script>
