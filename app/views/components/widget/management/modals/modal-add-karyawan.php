<?php
/**
 * Modal Add Karyawan Component
 * Modal for adding new employee
 */
?>

<!-- Modal Backdrop -->
<div id="modal-add-karyawan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Karyawan Baru</h3>
            <button type="button" id="close-modal-add-karyawan" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-karyawan-add" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; document.getElementById('placeholder-icon-karyawan-add').classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-karyawan-add" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p id="caution-text-add-karyawan" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="karyawan-image-input-add" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="karyawan-image-input-add" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-karyawan-add">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-karyawan-add'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-add-karyawan" class="space-y-[24px]">
            <!-- Hidden flag to remove photo -->
            <input type="hidden" id="remove-photo-add" name="remove_photo" value="0">
            
            <!-- Nama Karyawan -->
            <div>
                <label for="karyawan-name-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Karyawan
                </label>
                <input 
                    type="text" 
                    id="karyawan-name-add" 
                    name="karyawan_name"
                    placeholder="Isi nama karyawan"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Jabatan Karyawan (Dropdown) -->
            <div>
                <label for="karyawan-role-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Jabatan Karyawan
                </label>
                <div class="relative">
                    <select 
                        id="karyawan-role-add" 
                        name="karyawan_role"
                        class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft focus:outline-none focus:border-primary transition-colors appearance-none cursor-pointer"
                    >
                        <option value="">Isi jabatan karyawan</option>
                        <option value="Guru Kelas">Guru Kelas</option>
                        <option value="Guru Pendamping">Guru Pendamping</option>
                        <option value="Staff Administrasi">Staff Administrasi</option>
                        <option value="Staff Kebersihan">Staff Kebersihan</option>
                        <option value="Penjaga Sekolah">Penjaga Sekolah</option>
                    </select>
                    <!-- Dropdown Icon -->
                    <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-black-highlight pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-karyawan-add">
                    <?php component('button', [
                        'text' => 'Tambah Karyawan Baru',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-add-karyawan-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
// Delete image function (defined as global so it can be referenced from anywhere)
window.deleteImageAdd = function() {
    const imageInput = document.getElementById('karyawan-image-input-add');
    const previewImage = document.getElementById('preview-image-karyawan-add');
    const placeholderIcon = document.getElementById('placeholder-icon-karyawan-add');
    const cautionText = document.getElementById('caution-text-add-karyawan');
    const removePhotoFlag = document.getElementById('remove-photo-add');
    
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
    
    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-karyawan-add');
    if (deleteButtonWrapper) {
        deleteButtonWrapper.innerHTML = `
            <button type="button" id="delete-image-btn-karyawan-add" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                Hapus Gambar
            </button>
        `;
    }
};

// Function to open add modal
window.openAddKaryawanModal = function() {
    const modal = document.getElementById('modal-add-karyawan');
    modal.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-karyawan');
    const closeBtn = document.getElementById('close-modal-add-karyawan');
    const imageInput = document.getElementById('karyawan-image-input-add');
    const previewImage = document.getElementById('preview-image-karyawan-add');
    const placeholderIcon = document.getElementById('placeholder-icon-karyawan-add');
    const deleteImageBtn = document.getElementById('delete-image-btn-karyawan-add');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            resetAddKaryawanForm();
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            resetAddKaryawanForm();
        }
    });
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-add-karyawan');
            
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
                    
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-karyawan-add');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-karyawan-add" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        const newDeleteBtn = document.getElementById('delete-image-btn-karyawan-add');
                        if (newDeleteBtn) {
                            newDeleteBtn.addEventListener('click', window.deleteImageAdd);
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
        deleteImageBtn.addEventListener('click', window.deleteImageAdd);
    }
    
    // Form validation
    function validateAddKaryawanForm() {
        const karyawanName = document.getElementById('karyawan-name-add').value.trim();
        const karyawanRole = document.getElementById('karyawan-role-add').value;
        
        const allFilled = karyawanName && karyawanRole;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-karyawan-add');
        
        if (allFilled) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-karyawan-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-primary text-white hover:bg-primary-dark active:scale-95 cursor-pointer h-[52px]">
                    Tambah Karyawan Baru
                </button>
            `;
        } else {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-karyawan-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Karyawan Baru
                </button>
            `;
        }
    }
    
    window.validateAddKaryawanForm = validateAddKaryawanForm;
    
    const formInputs = ['karyawan-name-add', 'karyawan-role-add'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateAddKaryawanForm);
            input.addEventListener('change', validateAddKaryawanForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-add-karyawan');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            localStorage.setItem('activeManagementTabIndex', '1');
            
            const formData = new FormData();
            formData.append('name', document.getElementById('karyawan-name-add').value);
            formData.append('role', document.getElementById('karyawan-role-add').value);
            
            // Check if user wants to remove photo
            const removePhoto = document.getElementById('remove-photo-add').value;
            if (removePhoto === '1') {
                formData.append('remove_photo', '1');
            }
            
            if (imageInput.files[0]) {
                formData.append('photo', imageInput.files[0]);
            }
            
            fetch(`/admin/management/karyawan/create`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetAddKaryawanForm();
                    showToast('Karyawan berhasil ditambahkan!', 'success', 3000);
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
    function resetAddKaryawanForm() {
        if (form) form.reset();
        if (imageInput) imageInput.value = '';
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (placeholderIcon) placeholderIcon.classList.remove('hidden');
        
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-karyawan-add');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-karyawan-add" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-karyawan-add');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-karyawan-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah Karyawan Baru
                </button>
            `;
        }
    }
});
</script>
