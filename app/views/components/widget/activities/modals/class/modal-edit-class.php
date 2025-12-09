<?php
/**
 * Modal Edit Class Component
 * Modal for editing existing class
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-class" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Edit Kelas</h3>
            <button type="button" id="close-modal-edit-class" class="text-black-highlight hover:text-black-soft transition-colors">
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
                    <img id="preview-image-edit" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden" onerror="this.style.display='none'; document.getElementById('placeholder-icon-edit').classList.remove('hidden');">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-edit" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-edit-class" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload and Delete Buttons -->
            <div class="flex flex-col justify-between h-[124px]">
                <!-- Upload Button -->
                <label for="class-image-input-edit" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Gambar',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="class-image-input-edit" accept="image/*" class="hidden">
                
                <!-- Delete Button -->
                <div id="delete-button-wrapper-edit">
                    <?php component('button', [
                        'text' => 'Hapus Gambar',
                        'variant' => '10',
                        'type' => 'button',
                        'id' => 'delete-image-btn-edit'
                    ]); ?>
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-class" class="space-y-[24px]">
            <!-- Hidden ID field -->
            <input type="hidden" id="class-id-edit" name="class_id">
            
            <!-- Nama Kelas -->
            <div>
                <label for="class-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Kelas
                </label>
                <input 
                    type="text" 
                    id="class-name-edit" 
                    name="class_name"
                    placeholder="Isi nama kelas"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Rentang Usia Siswa di Kelas -->
            <div>
                <label for="age-range-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Rentang Usia Siswa di Kelas
                </label>
                <input 
                    type="text" 
                    id="age-range-edit" 
                    name="age_range"
                    placeholder="1,5 - 3"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Durasi Kelas per Hari and Maksimal Siswa -->
            <div class="grid grid-cols-2 gap-[20px]">
                <!-- Durasi Kelas per Hari -->
                <div>
                    <label for="duration-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Durasi Kelas per Hari
                    </label>
                    <input 
                        type="text" 
                        id="duration-edit" 
                        name="duration"
                        placeholder="2"
                        class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                    >
                </div>
                
                <!-- Maksimal Siswa di Dalam Kelas -->
                <div>
                    <label for="max-students-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Maksimal Siswa di Dalam Kelas
                    </label>
                    <input 
                        type="text" 
                        id="max-students-edit" 
                        name="max_students"
                        placeholder="10"
                        class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                    >
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex gap-[12px] items-center justify-center">
                <!-- Delete Class Button (Left) -->
                <?php component('button', [
                    'text' => 'Hapus Kelas',
                    'variant' => '13',
                    'type' => 'button',
                    'id' => 'delete-class-btn',
                    'icon' => 'trash'
                ]); ?>
                
                <!-- Update Class Button (Right) -->
                <div id="submit-button-wrapper-edit">
                    <?php component('button', [
                        'text' => 'Ubah Informasi Kelas',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-edit-class-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-class');
    const closeBtn = document.getElementById('close-modal-edit-class');
    const imageInput = document.getElementById('class-image-input-edit');
    const previewImage = document.getElementById('preview-image-edit');
    const placeholderIcon = document.getElementById('placeholder-icon-edit');
    const deleteImageBtn = document.getElementById('delete-image-btn-edit');
    const deleteClassBtn = document.getElementById('delete-class-btn');
    
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
            const cautionText = document.getElementById('caution-text-edit-class');
            
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
                    const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit');
                    if (deleteButtonWrapper) {
                        deleteButtonWrapper.innerHTML = `
                            <button type="button" id="delete-image-btn-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                                Hapus Gambar
                            </button>
                        `;
                        
                        // Re-attach event listener
                        const newDeleteBtn = document.getElementById('delete-image-btn-edit');
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
        const cautionText = document.getElementById('caution-text-edit-class');
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
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
            
            // Re-attach event listener
            const newDeleteBtn = document.getElementById('delete-image-btn-edit');
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
        const className = document.getElementById('class-name-edit').value.trim();
        const ageRange = document.getElementById('age-range-edit').value.trim();
        const duration = document.getElementById('duration-edit').value.trim();
        const maxStudents = document.getElementById('max-students-edit').value.trim();
        
        const allFilled = className && ageRange && duration && maxStudents;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit');
        
        if (allFilled) {
            // Change to variant 12
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-class-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-secondary text-white font-bold text-base hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Informasi Kelas
                </button>
            `;
        } else {
            // Change to variant 10
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-class-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Kelas
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['class-name-edit', 'age-range-edit', 'duration-edit', 'max-students-edit'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-edit-class');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const classId = document.getElementById('class-id-edit').value;
            if (!classId) {
                alert('Class ID tidak ditemukan');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('name', document.getElementById('class-name-edit').value);
            formData.append('age_range', document.getElementById('age-range-edit').value);
            formData.append('duration', document.getElementById('duration-edit').value);
            formData.append('max_students', document.getElementById('max-students-edit').value);
            
            // Add image if uploaded
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }
            
            // Send AJAX request
            fetch(`/admin/activities/classes/${classId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('Kelas berhasil diperbarui!', 'success', 3000);
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
    
    // Delete class button
    if (deleteClassBtn) {
        deleteClassBtn.addEventListener('click', function() {
            const classId = document.getElementById('class-id-edit').value;
            if (!classId) {
                showToast('Class ID tidak ditemukan', 'error', 3000);
                return;
            }
            
            if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
                fetch(`/admin/activities/classes/${classId}/delete`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modal.classList.add('hidden');
                        resetForm();
                        showToast('Kelas berhasil dihapus!', 'success', 3000);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast('Error: ' + data.message, 'error', 5000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menghapus data', 'error', 5000);
                });
            }
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
        const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit');
        if (deleteButtonWrapper) {
            deleteButtonWrapper.innerHTML = `
                <button type="button" id="delete-image-btn-edit" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Hapus Gambar
                </button>
            `;
        }
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-class-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Informasi Kelas
                </button>
            `;
        }
    }
    
    // Function to open modal with data (to be called from outside)
    window.openEditClassModal = function(classData) {
        // Set class ID
        document.getElementById('class-id-edit').value = classData.id || '';
        
        // Strip suffixes before populating form
        let age = (classData.age || '').replace(/ Tahun$/i, '').trim();
        let duration = (classData.duration || '').replace(/ jam$/i, '').trim();
        let maxStudents = (classData.maxStudents || '').replace(/ anak\/kelas$/i, '').trim();
        
        // Populate form with existing data
        document.getElementById('class-name-edit').value = classData.name || '';
        document.getElementById('age-range-edit').value = age;
        document.getElementById('duration-edit').value = duration;
        document.getElementById('max-students-edit').value = maxStudents;
        
        // Set image if exists
        if (classData.image) {
            const previewImage = document.getElementById('preview-image-edit');
            const placeholderIcon = document.getElementById('placeholder-icon-edit');
            
            if (previewImage && placeholderIcon) {
                previewImage.src = classData.image;
                previewImage.style.display = 'block';
                previewImage.classList.remove('hidden');
                placeholderIcon.classList.add('hidden');
            }
            
            // Change delete button to variant 11
            const deleteButtonWrapper = document.getElementById('delete-button-wrapper-edit');
            if (deleteButtonWrapper) {
                deleteButtonWrapper.innerHTML = `
                    <button type="button" id="delete-image-btn-edit" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-3 px-6 rounded-xl bg-white-neutral text-primary font-normal text-base leading-[28px] border border-border-light hover:bg-white-secondary cursor-pointer">
                        Hapus Gambar
                    </button>
                `;
                
                const newDeleteBtn = document.getElementById('delete-image-btn-edit');
                if (newDeleteBtn) {
                    newDeleteBtn.addEventListener('click', deleteImage);
                }
            }
        }
        
        // Validate form to enable submit button
        validateForm();
        
        // Show modal
        modal.classList.remove('hidden');
    };
});
</script>
