<?php
/**
 * Modal Edit Testimoni Component
 * Modal for editing existing testimonial
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-testimoni" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Edit Testimoni</h3>
            <button type="button" id="close-modal-edit-testimoni" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-edit-testimoni" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; document.getElementById('placeholder-icon-edit-testimoni').classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-edit-testimoni" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-edit-testimoni" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="testimoni-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="testimoni-image-input-edit" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-edit-testimoni">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-edit-testimoni'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-testimoni" class="space-y-[24px]">
            <!-- Hidden ID field -->
            <input type="hidden" id="testimoni-id-edit" name="testimoni_id">
            
            <!-- Nama Orang Tua and Nama Anak -->
            <div class="grid grid-cols-2 gap-[20px]">
                <!-- Nama Orang Tua -->
                <div>
                    <label for="parent-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Nama Orang Tua
                    </label>
                    <input 
                        type="text" 
                        id="parent-name-edit" 
                        name="parent_name"
                        placeholder="Isi nama orang tua"
                        class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                    >
                </div>
                
                <!-- Nama Anak -->
                <div>
                    <label for="child-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Nama Anak
                    </label>
                    <input 
                        type="text" 
                        id="child-name-edit" 
                        name="child_name"
                        placeholder="Isi nama anak"
                        class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                    >
                </div>
            </div>
            
            <!-- Testimoni -->
            <div>
                <label for="testimonial-text-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Testimoni
                </label>
                <textarea 
                    id="testimonial-text-edit" 
                    name="testimonial_text"
                    placeholder="Isi testimoni orang tua"
                    rows="4"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors resize-none"
                ></textarea>
            </div>
            
            <!-- Highlight Testimoni -->
            <div>
                <label for="highlight-text-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Highlight Testimoni
                </label>
                <input 
                    type="text" 
                    id="highlight-text-edit" 
                    name="highlight_text"
                    placeholder="Isi pesan testimoni yang dihighlight"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Action Button -->
            <div class="pt-[8px] flex justify-center">
                <!-- Update Testimoni Button -->
                <div id="submit-button-wrapper-edit-testimoni">
                    <?php component('button', [
                        'text' => 'Ubah Testimoni',
                        'variant' => '1',
                        'type' => 'submit',
                        'id' => 'submit-edit-testimoni-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
function initEditTestimoniModal() {
    const modal = document.getElementById('modal-edit-testimoni');
    const closeBtn = document.getElementById('close-modal-edit-testimoni');
    const imageInput = document.getElementById('testimoni-image-input-edit');
    const previewImage = document.getElementById('preview-image-edit-testimoni');
    const placeholderIcon = document.getElementById('placeholder-icon-edit-testimoni');
    const deleteImageBtn = document.getElementById('delete-image-btn-edit-testimoni');

    if (!modal || modal.dataset.initialized === 'true') {
        return;
    }

    modal.dataset.initialized = 'true';

    // Expose modal opener early so testimonial row clicks still work even if
    // another optional modal handler fails later during initialization.
    window.openEditTestimoniModal = function(testimoniData) {
        resetForm();

        document.getElementById('testimoni-id-edit').value = testimoniData.id || '';
        document.getElementById('parent-name-edit').value = testimoniData.parentName || '';
        document.getElementById('child-name-edit').value = testimoniData.childName || '';
        document.getElementById('testimonial-text-edit').value = testimoniData.testimonial || '';
        document.getElementById('highlight-text-edit').value = testimoniData.highlight || '';

        if (testimoniData.image) {
            const currentPreviewImage = document.getElementById('preview-image-edit-testimoni');
            const currentPlaceholderIcon = document.getElementById('placeholder-icon-edit-testimoni');

            if (currentPreviewImage && currentPlaceholderIcon) {
                currentPreviewImage.src = testimoniData.image;
                currentPreviewImage.style.display = 'block';
                currentPreviewImage.classList.remove('hidden');
                currentPlaceholderIcon.classList.add('hidden');
            }

            const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit-testimoni');
            if (deleteButtonWrapper) {
                deleteButtonWrapper.innerHTML = `
                    <button type="button" id="delete-image-btn-edit-testimoni" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                        Hapus Gambar
                    </button>
                `;

                const newDeleteBtn = document.getElementById('delete-image-btn-edit-testimoni');
                if (newDeleteBtn) {
                    newDeleteBtn.addEventListener('click', deleteImage);
                }
            }
        } else {
            if (previewImage) {
                previewImage.src = '';
                previewImage.classList.add('hidden');
            }

            if (placeholderIcon) {
                placeholderIcon.classList.remove('hidden');
            }
        }

        validateForm();
        modal.classList.remove('hidden');
    };
    
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
            const cautionText = document.getElementById('caution-text-edit-testimoni');
            
            if (file) {
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
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit-testimoni');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-edit-testimoni" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-edit-testimoni');
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
        const cautionText = document.getElementById('caution-text-edit-testimoni');
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        // Reset caution text
        if (cautionText) {
            cautionText.textContent = 'Maksimal ukuran file 2MB';
            cautionText.style.color = '<?= colors("white_shadow") ?>';
        }
        
        // Change delete button back to variant 10
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit-testimoni');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-edit-testimoni" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener
            const newDeleteBtn = document.getElementById('delete-image-btn-edit-testimoni');
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
        const parentName = document.getElementById('parent-name-edit').value.trim();
        const childName = document.getElementById('child-name-edit').value.trim();
        const testimonialText = document.getElementById('testimonial-text-edit').value.trim();
        const highlightText = document.getElementById('highlight-text-edit').value.trim();
        
        const allFilled = parentName && childName && testimonialText && highlightText;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-testimoni');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-testimoni-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Testimoni
                </button>
            `;
        } else {
            // Change to variant 10 (disabled)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-testimoni-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Testimoni
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['parent-name-edit', 'child-name-edit', 'testimonial-text-edit', 'highlight-text-edit'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-edit-testimoni');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const testimoniId = document.getElementById('testimoni-id-edit').value;
            if (!testimoniId) {
                alert('Testimoni ID tidak ditemukan');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('parent_name', document.getElementById('parent-name-edit').value);
            formData.append('child_name', document.getElementById('child-name-edit').value);
            formData.append('testimonial_text', document.getElementById('testimonial-text-edit').value);
            formData.append('highlight_text', document.getElementById('highlight-text-edit').value);
            
            // Add image if uploaded
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Send AJAX request
            fetch(`/admin/settings/testimonials/${testimoniId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('Testimoni berhasil diperbarui!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat mengubah data', 'error', 5000);
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
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit-testimoni');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-edit-testimoni" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-testimoni');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-testimoni-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Testimoni
                </button>
            `;
        }
    }
    
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditTestimoniModal);
} else {
    initEditTestimoniModal();
}
</script>
