<?php
/**
 * Modal Edit Gallery Image Fasilitas Component
 * Modal for editing gallery image in fasilitas
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-gallery-fasilitas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Informasi Foto</h3>
            <button type="button" id="close-modal-edit-gallery-fasilitas" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-gallery-fasilitas-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-gallery-fasilitas-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-gallery-fasilitas-edit" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload Button -->
            <div class="flex flex-col justify-between h-[124px]">
                <label for="gallery-image-input-fasilitas-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="gallery-image-input-fasilitas-edit" accept="image/*" class="hidden">
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-gallery-fasilitas" class="space-y-[24px]">
            <!-- Hidden fields -->
            <input type="hidden" id="gallery-image-id-fasilitas-edit" name="gallery_image_id">
            <input type="hidden" id="gallery-fasilitas-id-edit" name="fasilitas_id">
            
            <!-- Deskripsi Foto -->
            <div>
                <label for="photo-description-fasilitas-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Deskripsi Foto
                </label>
                <textarea 
                    id="photo-description-fasilitas-edit" 
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
                    id="set-as-cover-fasilitas-edit" 
                    name="set_as_cover"
                    class="w-[12px] h-[12px] border border-border-light rounded cursor-pointer"
                    style="accent-color: <?= colors('primary') ?>;"
                >
                <label for="set-as-cover-fasilitas-edit" class="ml-[8px] cursor-pointer" style="font-weight: 400; font-size: 12px; line-height: 20px; color: <?= colors('white_shadow') ?>;">
                    Jadikan foto ini sebagai sampul fasilitas (menggantikan sampul sebelumnya)
                </label>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center gap-[12px]" style="margin-top: 32px;">
                <!-- Delete Photo Button -->
                <div id="delete-photo-button-wrapper-fasilitas">
                    <?php component('button', [
                        'text' => 'Hapus Foto',
                        'variant' => '13',
                        'type' => 'button',
                        'id' => 'delete-gallery-photo-fasilitas-btn',
                        'icon' => 'trash',
                        'iconPosition' => 'left'
                    ]); ?>
                </div>
                
                <!-- Submit Button -->
                <div id="submit-button-wrapper-gallery-fasilitas-edit">
                    <?php component('button', [
                        'text' => 'Ubah Informasi Foto',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-edit-gallery-fasilitas-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Function to open edit gallery modal (will be called from management/index.php)
window.openEditGalleryFasilitasModal = function(galleryData) {
    const modal = document.getElementById('modal-edit-gallery-fasilitas');
    const previewImage = document.getElementById('preview-image-gallery-fasilitas-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-gallery-fasilitas-edit');
    
    // Store is_fasilitas_image flag
    modal.dataset.isFasilitasImage = galleryData.is_fasilitas_image ? 'true' : 'false';
    
    // Fill form fields
    document.getElementById('gallery-image-id-fasilitas-edit').value = galleryData.id;
    document.getElementById('gallery-fasilitas-id-edit').value = galleryData.fasilitas_id;
    document.getElementById('photo-description-fasilitas-edit').value = galleryData.description;
    document.getElementById('set-as-cover-fasilitas-edit').checked = galleryData.is_cover == 1;
    
    // Set image preview
    if (galleryData.image_path) {
        previewImage.src = galleryData.image_path;
        previewImage.style.display = ''; // Reset display style
        previewImage.classList.remove('hidden');
        placeholderIcon.classList.add('hidden');
    }
    
    // Always enable "Hapus Foto" button - no special handling for fasilitas.image
    const deletePhotoBtn = document.getElementById('delete-gallery-photo-fasilitas-btn');
    if (deletePhotoBtn) {
        deletePhotoBtn.disabled = false;
        deletePhotoBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        deletePhotoBtn.classList.add('cursor-pointer');
        deletePhotoBtn.title = '';
    }
    
    // Trigger form validation
    validateEditGalleryFasilitasForm();
    
    // Show modal
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-gallery-fasilitas');
    const closeBtn = document.getElementById('close-modal-edit-gallery-fasilitas');
    const imageInput = document.getElementById('gallery-image-input-fasilitas-edit');
    const previewImage = document.getElementById('preview-image-gallery-fasilitas-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-gallery-fasilitas-edit');
    const deletePhotoBtn = document.getElementById('delete-gallery-photo-fasilitas-btn');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetEditGalleryFasilitasForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetEditGalleryFasilitasForm();
        }
    });
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-gallery-fasilitas-edit');
            
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
                        previewImage.style.display = ''; // Reset display style
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
        const cautionText = document.getElementById('caution-text-gallery-fasilitas-edit');
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
    function validateEditGalleryFasilitasForm() {
        const photoDescription = document.getElementById('photo-description-fasilitas-edit').value.trim();
        
        const allFilled = photoDescription;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-fasilitas-edit');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-fasilitas-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Informasi Foto
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Foto
                </button>
            `;
        }
    }
    
    // Make validateEditGalleryFasilitasForm available globally
    window.validateEditGalleryFasilitasForm = validateEditGalleryFasilitasForm;
    
    // Add input listener for form validation
    const descInput = document.getElementById('photo-description-fasilitas-edit');
    if (descInput) {
        descInput.addEventListener('input', validateEditGalleryFasilitasForm);
    }
    
    // Form submit (Update)
    const form = document.getElementById('form-edit-gallery-fasilitas');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store active tab before submit to prevent glitch on reload
            localStorage.setItem('activeManagementTabIndex', '2');
            
            const modal = document.getElementById('modal-edit-gallery-fasilitas');
            const isFasilitasImage = modal.dataset.isFasilitasImage === 'true';
            const galleryImageId = document.getElementById('gallery-image-id-fasilitas-edit').value;
            const fasilitasId = document.getElementById('gallery-fasilitas-id-edit').value;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('description', document.getElementById('photo-description-fasilitas-edit').value);
            formData.append('is_cover', document.getElementById('set-as-cover-fasilitas-edit').checked ? '1' : '0');
            
            // Add image if uploaded (optional)
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Choose endpoint based on whether this is fasilitas.image or gallery image
            let endpoint;
            if (isFasilitasImage) {
                // Update fasilitas.image via special endpoint
                endpoint = `/admin/management/fasilitas/${fasilitasId}/update-fasilitas-image`;
            } else {
                // Update gallery image
                endpoint = `/admin/management/fasilitas/${fasilitasId}/gallery/${galleryImageId}/update`;
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
                    resetEditGalleryFasilitasForm();
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
            const modal = document.getElementById('modal-edit-gallery-fasilitas');
            const isFasilitasImage = modal.dataset.isFasilitasImage === 'true';
            
            // Check if there's a new uploaded image in the file input
            if (imageInput.files[0]) {
                // Just remove the preview of newly uploaded image
                deleteImagePreview();
                showToast('Preview gambar baru telah dihapus', 'info', 2000);
            } else {
                // Permanent delete
                if (isFasilitasImage) {
                    // Delete fasilitas.image
                    openModalDeleteFasilitasImage(
                        'Hapus Gambar Fasilitas',
                        'Apakah Anda yakin ingin menghapus gambar fasilitas ini? Tindakan ini tidak dapat dibatalkan.',
                        function() {
                            // Store active tab before delete
                            localStorage.setItem('activeManagementTabIndex', '2');
                            
                            const fasilitasId = document.getElementById('gallery-fasilitas-id-edit').value;
                            
                            // Send AJAX request to delete fasilitas.image
                            fetch(`/admin/management/fasilitas/${fasilitasId}/delete-fasilitas-image`, {
                                method: 'POST'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    modal.classList.add('hidden');
                                    resetEditGalleryFasilitasForm();
                                    showToast('Gambar fasilitas berhasil dihapus!', 'success', 3000);
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
                } else {
                    // Delete gallery image
                    openModalDeleteGalleryImage(
                        'Hapus Foto Galeri',
                        'Apakah Anda yakin ingin menghapus foto ini dari galeri? Tindakan ini tidak dapat dibatalkan.',
                        function() {
                            // Store active tab before delete to prevent glitch on reload
                            localStorage.setItem('activeManagementTabIndex', '2');
                            
                            const galleryImageId = document.getElementById('gallery-image-id-fasilitas-edit').value;
                            const fasilitasId = document.getElementById('gallery-fasilitas-id-edit').value;
                            
                            // Send AJAX request
                            fetch(`/admin/management/fasilitas/${fasilitasId}/gallery/${galleryImageId}/delete`, {
                                method: 'POST'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    modal.classList.add('hidden');
                                    resetEditGalleryFasilitasForm();
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
                    );
                }
            }
        });
    }
    
    // Reset form
    function resetEditGalleryFasilitasForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        // Reset caution text
        const cautionText = document.getElementById('caution-text-gallery-fasilitas-edit');
        if (cautionText) {
            cautionText.textContent = 'Maksimal ukuran file 2MB';
            cautionText.style.color = '<?= colors("white_shadow") ?>';
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-fasilitas-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-gallery-fasilitas-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Foto
                </button>
            `;
        }
    }
});
</script>
