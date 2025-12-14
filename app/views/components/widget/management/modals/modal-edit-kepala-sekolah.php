<?php
/**
 * Modal Edit Kepala Sekolah Component
 * Modal for editing principal information
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-kepala-sekolah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Informasi Kepala Sekolah</h3>
            <button type="button" id="close-modal-edit-kepala-sekolah" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-principal-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; document.getElementById('placeholder-icon-principal-edit').classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-principal-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p id="caution-text-edit-principal" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="principal-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="principal-image-input-edit" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-principal-edit">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-principal-edit'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-kepala-sekolah" class="space-y-[24px]">
            <!-- Hidden field for principal ID -->
            <input type="hidden" id="principal-id-edit" name="principal_id">
            <!-- Hidden flag to remove photo -->
            <input type="hidden" id="remove-photo-principal" name="remove_photo" value="0">
            
            <!-- Nama Kepala Sekolah -->
            <div>
                <label for="principal-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Kepala Sekolah
                </label>
                <input 
                    type="text" 
                    id="principal-name-edit" 
                    name="principal_name"
                    placeholder="Isi nama kepala sekolah"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-principal-edit">
                    <?php component('button', [
                        'text' => 'Ubah Informasi Kepala Sekolah',
                        'variant' => '1',
                        'type' => 'submit',
                        'id' => 'submit-edit-principal-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Delete image function (defined as global so it can be referenced from anywhere)
window.deleteImagePrincipal = function() {
    const imageInput = document.getElementById('principal-image-input-edit');
    const previewImage = document.getElementById('preview-image-principal-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-principal-edit');
    const cautionText = document.getElementById('caution-text-edit-principal');
    const removePhotoFlag = document.getElementById('remove-photo-principal');
    
    if (imageInput) imageInput.value = '';
    if (previewImage) {
        previewImage.src = '';
        previewImage.classList.add('hidden');
    }
    if (placeholderIcon) placeholderIcon.classList.remove('hidden');
    
    // Set flag to remove photo on submit
    if (removePhotoFlag) {
        removePhotoFlag.value = '1';
    }
    
    if (cautionText) {
        cautionText.textContent = 'Maksimal ukuran file 2MB';
        cautionText.style.color = '<?= colors("white_shadow") ?>';
    }
    
    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-principal-edit');
    if (deleteButtonWrapper) {
        deleteButtonWrapper.innerHTML = `
            <button type="button" id="delete-image-btn-principal-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                Hapus Gambar
            </button>
        `;
    }
};

// Function to open edit modal
window.openEditKepalaSekolahModal = function(principalData) {
    console.log('Opening principal modal with data:', principalData); // Debug log
    
    const modal = document.getElementById('modal-edit-kepala-sekolah');
    const previewImage = document.getElementById('preview-image-principal-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-principal-edit');
    const removePhotoFlag = document.getElementById('remove-photo-principal');
    
    // Reset remove photo flag
    if (removePhotoFlag) {
        removePhotoFlag.value = '0';
    }
    
    // Set form values
    document.getElementById('principal-id-edit').value = principalData.id || '';
    document.getElementById('principal-name-edit').value = principalData.name || '';
    
    // Handle photo display
    if (principalData.photo) {
        previewImage.src = principalData.photo;
        previewImage.style.display = 'block';
        previewImage.classList.remove('hidden');
        placeholderIcon.classList.add('hidden');
        
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-principal-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-principal-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener to new button
            const newDeleteBtn = document.getElementById('delete-image-btn-principal-edit');
            if (newDeleteBtn) {
                newDeleteBtn.addEventListener('click', window.deleteImagePrincipal);
            }
        }
    } else {
        // No photo - show placeholder
        previewImage.src = '';
        previewImage.classList.add('hidden');
        placeholderIcon.classList.remove('hidden');
    }
    
    validateEditPrincipalForm();
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-kepala-sekolah');
    const closeBtn = document.getElementById('close-modal-edit-kepala-sekolah');
    const imageInput = document.getElementById('principal-image-input-edit');
    const previewImage = document.getElementById('preview-image-principal-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-principal-edit');
    const deleteImageBtn = document.getElementById('delete-image-btn-principal-edit');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetEditPrincipalForm();
        });
    }
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetEditPrincipalForm();
        }
    });
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-edit-principal');
            
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showToast('Tipe file tidak valid. Hanya JPG, PNG, GIF, dan WEBP yang diperbolehkan.', 'error', 3000);
                    imageInput.value = '';
                    return;
                }
                
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
                    
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-principal-edit');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-principal-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        const newDeleteBtn = document.getElementById('delete-image-btn-principal-edit');
                        if (newDeleteBtn) {
                            newDeleteBtn.addEventListener('click', window.deleteImagePrincipal);
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
    
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', window.deleteImagePrincipal);
    }
    
    function validateEditPrincipalForm() {
        const principalName = document.getElementById('principal-name-edit').value.trim();
        
        const allFilled = principalName;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-principal-edit');
        
        if (allFilled) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-principal-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-primary text-white hover:bg-primary-dark active:scale-95 cursor-pointer h-[52px]">
                    Ubah Informasi Kepala Sekolah
                </button>
            `;
        } else {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-principal-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Kepala Sekolah
                </button>
            `;
        }
    }
    
    window.validateEditPrincipalForm = validateEditPrincipalForm;
    
    const input = document.getElementById('principal-name-edit');
    if (input) {
        input.addEventListener('input', validateEditPrincipalForm);
    }
    
    const form = document.getElementById('form-edit-kepala-sekolah');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            localStorage.setItem('activeManagementTabIndex', '1');
            
            const principalId = document.getElementById('principal-id-edit').value;
            
            const formData = new FormData();
            formData.append('name', document.getElementById('principal-name-edit').value);
            
            // Check if user wants to remove photo
            const removePhoto = document.getElementById('remove-photo-principal').value;
            if (removePhoto === '1') {
                formData.append('remove_photo', '1');
            }
            
            if (imageInput.files[0]) {
                formData.append('photo', imageInput.files[0]);
            }
            
            fetch(`/admin/management/kepala-sekolah/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetEditPrincipalForm();
                    showToast('Kepala Sekolah berhasil diubah!', 'success', 3000);
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
    
    function resetEditPrincipalForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-principal-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-principal-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-principal-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-principal-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Kepala Sekolah
                </button>
            `;
        }
    }
});
</script>
