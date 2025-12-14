<?php
/**
 * Modal Edit Prakata Sekolah Component
 * Modal for editing school introduction/foreword
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-prakata" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Prakata Sekolah</h3>
            <button type="button" id="close-modal-edit-prakata" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-prakata-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; document.getElementById('placeholder-icon-prakata-edit').classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-prakata-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-edit-prakata" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="prakata-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="prakata-image-input-edit" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-prakata-edit">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-prakata-edit'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-prakata" class="space-y-[24px]">
            <!-- Hidden field for prakata ID -->
            <input type="hidden" id="prakata-id-edit" name="prakata_id">
            
            <!-- Judul Prakata -->
            <div>
                <label for="prakata-title-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Judul Prakata Sekolah
                </label>
                <input 
                    type="text" 
                    id="prakata-title-edit" 
                    name="prakata_title"
                    placeholder="Isi judul prakata"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Deskripsi Program -->
            <div>
                <label for="prakata-description-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Deskripsi Program
                </label>
                <textarea 
                    id="prakata-description-edit" 
                    name="prakata_description"
                    placeholder="Isi deskripsi program"
                    rows="5"
                    maxlength="600"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors resize-none"
                ></textarea>
                <!-- Character Counter -->
                <p id="char-counter" class="text-[12px] text-black-highlight mt-2">Maksimal 0/600 Karakter</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-prakata-edit">
                    <?php component('button', [
                        'text' => 'Ubah Prakata Sekolah',
                        'variant' => '1',
                        'type' => 'submit',
                        'id' => 'submit-edit-prakata-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Function to open edit modal (will be called from management/index.php)
window.openEditPrakataModal = function(prakataData) {
    const modal = document.getElementById('modal-edit-prakata');
    const previewImage = document.getElementById('preview-image-prakata-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-prakata-edit');
    const charCounter = document.getElementById('char-counter');
    
    // Fill form fields
    document.getElementById('prakata-id-edit').value = prakataData.id;
    document.getElementById('prakata-title-edit').value = prakataData.title;
    document.getElementById('prakata-description-edit').value = prakataData.description;
    
    // Update character counter
    const descLength = prakataData.description.length;
    charCounter.textContent = `Maksimal ${descLength}/600 Karakter`;
    
    // Set image preview if exists
    if (prakataData.image) {
        previewImage.src = prakataData.image;
        previewImage.style.display = 'block';
        previewImage.classList.remove('hidden');
        placeholderIcon.classList.add('hidden');
        
        // Change delete button to variant 11
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-prakata-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-prakata-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                    Hapus Gambar
                </button>
            `;
        }
    }
    
    // Trigger form validation
    validateEditPrakataForm();
    
    // Show modal
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-prakata');
    const closeBtn = document.getElementById('close-modal-edit-prakata');
    const imageInput = document.getElementById('prakata-image-input-edit');
    const previewImage = document.getElementById('preview-image-prakata-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-prakata-edit');
    const deleteImageBtn = document.getElementById('delete-image-btn-prakata-edit');
    const descriptionTextarea = document.getElementById('prakata-description-edit');
    const charCounter = document.getElementById('char-counter');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetEditPrakataForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetEditPrakataForm();
        }
    });
    
    // Character counter for description
    if (descriptionTextarea && charCounter) {
        descriptionTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCounter.textContent = `Maksimal ${currentLength}/600 Karakter`;
        });
    }
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-edit-prakata');
            
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
                        previewImage.style.display = 'block';
                        previewImage.classList.remove('hidden');
                        placeholderIcon.classList.add('hidden');
                    }
                    
                    // Change delete button to variant 11
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-prakata-edit');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-prakata-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-prakata-edit');
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
        const cautionText = document.getElementById('caution-text-edit-prakata');
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
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-prakata-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-prakata-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener
            const newDeleteBtn = document.getElementById('delete-image-btn-prakata-edit');
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
    function validateEditPrakataForm() {
        const prakataTitle = document.getElementById('prakata-title-edit').value.trim();
        const prakataDescription = document.getElementById('prakata-description-edit').value.trim();
        
        const allFilled = prakataTitle && prakataDescription;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-prakata-edit');
        
        if (allFilled) {
            // Change to variant 1
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-prakata-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-primary text-white hover:bg-primary-dark active:scale-95 cursor-pointer">
                    Ubah Prakata Sekolah
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-prakata-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Prakata Sekolah
                </button>
            `;
        }
    }
    
    // Make validateEditPrakataForm available globally
    window.validateEditPrakataForm = validateEditPrakataForm;
    
    // Add input listeners for form validation
    const formInputs = ['prakata-title-edit', 'prakata-description-edit'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateEditPrakataForm);
        }
    });
    
    // Form submit (Update)
    const form = document.getElementById('form-edit-prakata');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Store active tab before submit to prevent glitch on reload
            localStorage.setItem('activeManagementTabIndex', '0');
            
            const prakataId = document.getElementById('prakata-id-edit').value;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('title', document.getElementById('prakata-title-edit').value);
            formData.append('description', document.getElementById('prakata-description-edit').value);
            
            // Add image if uploaded
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Send AJAX request
            fetch(`/admin/management/prakata/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetEditPrakataForm();
                    showToast('Prakata berhasil diubah!', 'success', 3000);
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
    
    // Reset form
    function resetEditPrakataForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        if (charCounter) charCounter.textContent = 'Maksimal 0/600 Karakter';
        
        // Reset delete button to variant 10
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-prakata-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-prakata-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-prakata-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-prakata-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Prakata Sekolah
                </button>
            `;
        }
    }
});
</script>
