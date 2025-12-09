<?php
/**
 * Modal Add Gallery Image Component
 * Modal for adding gallery image to program harian
 */
?>

<!-- Modal Backdrop -->
<div id="modal-add-gallery-harian" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Foto ke Galeri</h3>
            <button type="button" id="close-modal-add-gallery-harian" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-gallery-harian" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-gallery-harian" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-gallery-harian" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="gallery-harian-image-input" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="gallery-harian-image-input" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-gallery-harian">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-gallery-harian'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-add-gallery-harian" class="space-y-[24px]">
            <!-- Hidden field for program ID -->
            <input type="hidden" id="gallery-harian-program-id" name="program_id">
            
            <!-- Deskripsi Foto -->
            <div>
                <label for="photo-description-harian" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Deskripsi Foto
                </label>
                <textarea 
                    id="photo-description-harian" 
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
                    id="set-as-cover-harian" 
                    name="set_as_cover"
                    class="w-[12px] h-[12px] border border-border-light rounded cursor-pointer"
                    style="accent-color: <?= colors('primary') ?>;"
                >
                <label for="set-as-cover-harian" class="ml-[8px] cursor-pointer" style="font-weight: 400; font-size: 12px; line-height: 20px; color: <?= colors('white_shadow') ?>;">
                    Jadikan foto ini sebagai sampul program (menggantikan sampul sebelumnya)
                </label>
            </div>
            
            <!-- Submit Button -->
            <div class="pt-[8px] flex justify-center" style="margin-top: 32px;">
                <div id="submit-button-wrapper-gallery-harian">
                    <?php component('button', [
                        'text' => 'Tambah Foto ke Galeri',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-add-gallery-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Function to open gallery modal (will be called from activities/index.php)
window.openAddGalleryHarianModal = function(programId) {
    const modal = document.getElementById('modal-add-gallery-harian');
    
    console.log('Opening Add Gallery Modal - Program ID:', programId);
    
    // Validate program ID
    if (!programId || programId === 'undefined') {
        showToast('Error: Program ID tidak valid', 'error', 3000);
        console.error('Invalid program ID:', programId);
        return;
    }
    
    // Set program ID
    document.getElementById('gallery-harian-program-id').value = programId;
    
    // Show modal
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-gallery-harian');
    const closeBtn = document.getElementById('close-modal-add-gallery-harian');
    const imageInput = document.getElementById('gallery-harian-image-input');
    const previewImage = document.getElementById('preview-image-gallery-harian');
    const placeholderIcon = document.getElementById('placeholder-icon-gallery-harian');
    const deleteImageBtn = document.getElementById('delete-image-btn-gallery-harian');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetForm();
        }
    });
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-gallery-harian');
            
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
                    
                    // Change delete button to variant 11
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-gallery-harian');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-gallery-harian" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-gallery-harian');
                        if (newDeleteBtn) {
                            newDeleteBtn.addEventListener('click', deleteImage);
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
    
    // Delete image function
    function deleteImage() {
        const cautionText = document.getElementById('caution-text-gallery-harian');
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
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-gallery-harian');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-gallery-harian" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener
            const newDeleteBtn = document.getElementById('delete-image-btn-gallery-harian');
            if (newDeleteBtn) {
                newDeleteBtn.addEventListener('click', deleteImage);
            }
        }
    }
    
    // Delete image
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', deleteImage);
    }
    
    // Form validation
    function validateForm() {
        const photoDescription = document.getElementById('photo-description-harian').value.trim();
        const hasImage = imageInput.files.length > 0;
        
        const allFilled = photoDescription && hasImage;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-harian');
        
        if (allFilled) {
            // Change to variant 12
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-gallery-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-secondary text-white font-bold text-base hover:opacity-90 active:scale-95 cursor-pointer">
                    Tambah Foto ke Galeri
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-gallery-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Foto ke Galeri
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const descInput = document.getElementById('photo-description-harian');
    if (descInput) {
        descInput.addEventListener('input', validateForm);
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', validateForm);
    }
    
    // Form submit
    const form = document.getElementById('form-add-gallery-harian');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store active tab before submit to prevent glitch on reload
            localStorage.setItem('activeTabIndex', '2');
            
            const programId = document.getElementById('gallery-harian-program-id').value;
            
            console.log('Submitting form - Program ID:', programId);
            
            // Validate program ID before submission
            if (!programId || programId === 'undefined' || programId === '') {
                showToast('Error: Program ID tidak valid. Silakan tutup modal dan coba lagi.', 'error', 5000);
                console.error('Invalid program ID on submit:', programId);
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('description', document.getElementById('photo-description-harian').value);
            formData.append('set_as_cover', document.getElementById('set-as-cover-harian').checked ? '1' : '0');
            
            // Add image (required)
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Send AJAX request
            fetch(`/admin/activities/programs-harian/${programId}/gallery/store`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('Foto berhasil ditambahkan ke galeri!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast('Error: ' + data.message, 'error', 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan data', 'error', 5000);
            });
        });
    }
    
    // Reset form
    function resetForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        // Reset delete button to variant 10
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-gallery-harian');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-gallery-harian" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-gallery-harian');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-gallery-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Foto ke Galeri
                </button>
            `;
        }
    }
});
</script>
