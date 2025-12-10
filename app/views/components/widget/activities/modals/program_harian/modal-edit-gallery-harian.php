<?php
/**
 * Modal Edit Gallery Image Component
 * Modal for editing gallery image in program harian
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-gallery-harian" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Informasi Foto</h3>
            <button type="button" id="close-modal-edit-gallery-harian" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Image Upload Section -->
        <div class="flex items-start gap-[20px] mb-[24px] justify-center">
            <!-- Image Preview Container with Caution -->
            <div class="flex flex-col items-center gap-[8px]">
                <div class="w-[124px] h-[124px] rounded-[14px] bg-gray-placeholder flex items-center justify-center flex-shrink-0">
                    <img id="preview-image-gallery-harian-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-gallery-harian-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-gallery-harian-edit" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload Button -->
            <div class="flex flex-col justify-between h-[124px]">
                <label for="gallery-harian-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="gallery-harian-image-input-edit" accept="image/*" class="hidden">
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-gallery-harian" class="space-y-[24px]">
            <!-- Hidden fields -->
            <input type="hidden" id="gallery-harian-image-id-edit" name="gallery_image_id">
            <input type="hidden" id="gallery-harian-program-id-edit" name="program_id">
            
            <!-- Deskripsi Foto -->
            <div>
                <label for="photo-description-harian-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Deskripsi Foto
                </label>
                <textarea 
                    id="photo-description-harian-edit" 
                    name="photo_description"
                    placeholder="Isi deskripsi foto"
                    rows="5"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors resize-none"
                ></textarea>
            </div>
            
            <!-- Checkbox: Jadikan foto sebagai sampul -->
            <div class="flex items-center" style="margin-top: 12px;">
                <input 
                    type="checkbox" 
                    id="set-as-cover-harian-edit" 
                    name="set_as_cover"
                    class="w-[12px] h-[12px] border border-border-light rounded cursor-pointer"
                    style="accent-color: <?= colors('primary') ?>;"
                >
                <label for="set-as-cover-harian-edit" class="ml-[8px] cursor-pointer" style="font-weight: 400; font-size: 12px; line-height: 20px; color: <?= colors('white_shadow') ?>;">
                    Jadikan foto ini sebagai sampul program (menggantikan sampul sebelumnya)
                </label>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center gap-[12px]" style="margin-top: 32px;">
                <!-- Delete Photo Button -->
                <div id="delete-photo-button-wrapper">
                    <?php component('button', [
                        'text' => 'Hapus Foto',
                        'variant' => '13',
                        'type' => 'button',
                        'id' => 'delete-gallery-harian-photo-btn',
                        'icon' => 'trash',
                        'iconPosition' => 'left'
                    ]); ?>
                </div>
                
                <!-- Submit Button -->
                <div id="submit-button-wrapper-gallery-harian-edit">
                    <?php component('button', [
                        'text' => 'Ubah Informasi Foto',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-edit-gallery-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Function to open edit gallery modal (will be called from activities/index.php)
window.openEditGalleryHarianModal = function(galleryData) {
    const modal = document.getElementById('modal-edit-gallery-harian');
    const previewImage = document.getElementById('preview-image-gallery-harian-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-gallery-harian-edit');
    
    console.log('Opening Edit Gallery Modal - Gallery Data:', galleryData);
    
    // Validate program ID
    if (!galleryData.program_id || galleryData.program_id === 'undefined') {
        showToast('Error: Program ID tidak valid', 'error', 3000);
        console.error('Invalid program ID:', galleryData);
        return;
    }
    
    // Store is_program_image flag
    modal.dataset.isProgramImage = galleryData.is_program_image ? 'true' : 'false';
    
    // Fill form fields
    document.getElementById('gallery-harian-image-id-edit').value = galleryData.id || '';
    document.getElementById('gallery-harian-program-id-edit').value = galleryData.program_id;
    document.getElementById('photo-description-harian-edit').value = galleryData.description;
    document.getElementById('set-as-cover-harian-edit').checked = galleryData.is_cover == 1;
    
    // Set image preview
    if (galleryData.image_path) {
        previewImage.src = galleryData.image_path;
        previewImage.classList.remove('hidden');
        placeholderIcon.classList.add('hidden');
    }
    
    // Always enable "Hapus Foto" button - no special handling for program.image
    const deletePhotoBtn = document.getElementById('delete-gallery-harian-photo-btn');
    if (deletePhotoBtn) {
        deletePhotoBtn.disabled = false;
        deletePhotoBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        deletePhotoBtn.classList.add('cursor-pointer');
        deletePhotoBtn.title = '';
    }
    
    // Trigger form validation
    validateEditGalleryHarianForm();
    
    // Show modal
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-gallery-harian');
    const closeBtn = document.getElementById('close-modal-edit-gallery-harian');
    const imageInput = document.getElementById('gallery-harian-image-input-edit');
    const previewImage = document.getElementById('preview-image-gallery-harian-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-gallery-harian-edit');
    const deletePhotoBtn = document.getElementById('delete-gallery-harian-photo-btn');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetEditGalleryForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetEditGalleryForm();
        }
    });
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-gallery-harian-edit');
            
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
                        previewImage.classList.remove('hidden');
                        placeholderIcon.classList.add('hidden');
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
    
    // Delete image function (remove preview only - for new uploads)
    function deleteImagePreview() {
        const cautionText = document.getElementById('caution-text-gallery-harian-edit');
        imageInput.value = '';
        previewImage.src = '';
        previewImage.classList.add('hidden');
        placeholderIcon.classList.remove('hidden');
        
        // Reset caution text
        if (cautionText) {
            cautionText.textContent = 'Maksimal ukuran file 2MB';
            cautionText.style.color = '<?= colors("white_shadow") ?>';
        }
    }
    
    // Form validation
    function validateEditGalleryHarianForm() {
        const photoDescription = document.getElementById('photo-description-harian-edit').value.trim();
        
        const allFilled = photoDescription;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-harian-edit');
        
        if (allFilled) {
            // Change to variant 12
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-secondary text-white font-bold text-base hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Informasi Foto
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Foto
                </button>
            `;
        }
    }
    
    // Make validateEditGalleryHarianForm available globally
    window.validateEditGalleryHarianForm = validateEditGalleryHarianForm;
    
    // Add input listener for form validation
    const descInput = document.getElementById('photo-description-harian-edit');
    if (descInput) {
        descInput.addEventListener('input', validateEditGalleryHarianForm);
    }
    
    // Form submit (Update)
    const form = document.getElementById('form-edit-gallery-harian');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store active tab before submit to prevent glitch on reload
            localStorage.setItem('activeTabIndex', '2');
            
            const modal = document.getElementById('modal-edit-gallery-harian');
            const isProgramImage = modal.dataset.isProgramImage === 'true';
            const galleryImageId = document.getElementById('gallery-harian-image-id-edit').value;
            const programId = document.getElementById('gallery-harian-program-id-edit').value;
            
            console.log('Submitting edit form - Program ID:', programId, 'Gallery ID:', galleryImageId);
            
            // Validate program ID
            if (!programId || programId === 'undefined' || programId === '') {
                showToast('Error: Program ID tidak valid. Silakan tutup modal dan coba lagi.', 'error', 5000);
                console.error('Invalid program ID on submit:', programId);
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('description', document.getElementById('photo-description-harian-edit').value);
            formData.append('is_cover', document.getElementById('set-as-cover-harian-edit').checked ? '1' : '0');
            
            // Add image if uploaded (optional)
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Choose endpoint based on whether this is program.image or gallery image
            let endpoint;
            if (isProgramImage) {
                // Update program.image via special endpoint
                endpoint = `/admin/activities/programs-harian/${programId}/update-program-image`;
            } else {
                // Update gallery image
                endpoint = `/admin/activities/programs-harian/${programId}/gallery/${galleryImageId}/update`;
            }
            
            // Send AJAX request
            fetch(endpoint, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetEditGalleryForm();
                    showToast('Foto berhasil diubah!', 'success', 3000);
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
    
    // Delete photo (handles both preview removal and permanent deletion)
    if (deletePhotoBtn) {
        deletePhotoBtn.addEventListener('click', function() {
            const modal = document.getElementById('modal-edit-gallery-harian');
            const isProgramImage = modal.dataset.isProgramImage === 'true';
            
            // Check if there's a new uploaded image in the file input
            if (imageInput.files[0]) {
                // Just remove the preview of newly uploaded image
                deleteImagePreview();
                showToast('Preview gambar baru telah dihapus', 'info', 2000);
            } else {
                // Permanent delete
                if (isProgramImage) {
                    // Delete program.image
                    if (confirm('Apakah Anda yakin ingin menghapus gambar program ini?')) {
                        // Store active tab before delete
                        localStorage.setItem('activeTabIndex', '2');
                        
                        const programId = document.getElementById('gallery-harian-program-id-edit').value;
                        
                        console.log('Deleting program image - Program ID:', programId);
                        
                        // Validate program ID
                        if (!programId || programId === 'undefined' || programId === '') {
                            showToast('Error: Program ID tidak valid. Silakan tutup modal dan coba lagi.', 'error', 5000);
                            console.error('Invalid program ID on delete:', programId);
                            return;
                        }
                        
                        // Send AJAX request to delete program.image
                        fetch(`/admin/activities/programs-harian/${programId}/delete-program-image`, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modal.classList.add('hidden');
                                resetEditGalleryForm();
                                showToast('Gambar program berhasil dihapus!', 'success', 3000);
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
                } else {
                    // Delete gallery image
                    if (confirm('Apakah Anda yakin ingin menghapus foto ini dari galeri?')) {
                        // Store active tab before delete to prevent glitch on reload
                        localStorage.setItem('activeTabIndex', '2');
                        
                        const galleryImageId = document.getElementById('gallery-harian-image-id-edit').value;
                        const programId = document.getElementById('gallery-harian-program-id-edit').value;
                        
                        console.log('Deleting gallery image - Program ID:', programId, 'Gallery ID:', galleryImageId);
                        
                        // Validate IDs
                        if (!programId || programId === 'undefined' || programId === '') {
                            showToast('Error: Program ID tidak valid. Silakan tutup modal dan coba lagi.', 'error', 5000);
                            console.error('Invalid program ID on delete:', programId);
                            return;
                        }
                        
                        if (!galleryImageId || galleryImageId === 'undefined' || galleryImageId === '') {
                            showToast('Error: Gallery ID tidak valid. Silakan tutup modal dan coba lagi.', 'error', 5000);
                            console.error('Invalid gallery ID on delete:', galleryImageId);
                            return;
                        }
                        
                        // Send AJAX request
                        fetch(`/admin/activities/programs-harian/${programId}/gallery/${galleryImageId}/delete`, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modal.classList.add('hidden');
                                resetEditGalleryForm();
                                showToast('Foto berhasil dihapus!', 'success', 3000);
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
                }
            }
        });
    }
    
    // Reset form
    function resetEditGalleryForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        // Reset caution text
        const cautionText = document.getElementById('caution-text-gallery-harian-edit');
        if (cautionText) {
            cautionText.textContent = 'Maksimal ukuran file 2MB';
            cautionText.style.color = '<?= colors("white_shadow") ?>';
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-harian-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Foto
                </button>
            `;
        }
    }
});
</script>
