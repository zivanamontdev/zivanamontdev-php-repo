<?php
/**
 * Modal Publish Article Component
 * Modal for publishing article with author and image
 */
?>

<!-- Modal Backdrop -->
<div id="modal-publish-article" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 id="modal-title" class="font-bold text-[20px] leading-[100%] text-black-soft">Publish Artikel</h3>
            <button type="button" id="close-modal-publish-article" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Image Upload Section (Align Start) -->
        <div class="flex items-start gap-[20px] mb-[24px]">
            <!-- Image Preview Container with Caution -->
            <div class="flex flex-col items-center gap-[8px]">
                <div class="w-[124px] h-[124px] rounded-[14px] bg-gray-placeholder flex items-center justify-center flex-shrink-0">
                    <img id="preview-image-article" src="" alt="Preview" class="w-full h-full object-cover rounded-[14px] hidden">
                    <svg class="w-12 h-12 text-white-shadow" id="placeholder-icon-article" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p id="caution-text-article" class="text-[10px] leading-[16px] text-white-shadow font-normal">Maksimal ukuran file 2MB</p>
            </div>
            
            <!-- Upload Button (Align Start) -->
            <div class="flex flex-col justify-start">
                <!-- Upload Button -->
                <label for="article-image-input" class="cursor-pointer">
                    <?php component('button', [
                        'text' => 'Upload Foto',
                        'variant' => '6',
                        'type' => 'button',
                        'class' => 'pointer-events-none'
                    ]); ?>
                </label>
                <input type="file" id="article-image-input" accept="image/*" class="hidden">
            </div>
        </div>
        
        <!-- Form Fields -->
        <form id="form-publish-article" class="space-y-[24px]">
            <!-- Nama Penulis -->
            <div class="mb-8">
                <label for="author-name" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Penulis
                </label>
                <input 
                    type="text" 
                    id="author-name" 
                    name="author_name"
                    placeholder="Isi nama penulis"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                />
            </div>
            
            <!-- Caution Text -->
            <div class="mb-8">
                <p class="font-normal text-[16px] leading-[26px] text-black-highlight">
                    Artikel yang dipublish akan muncul di website utama pada halaman artikel dan dapat diakses oleh semua orang. Pastikan isi dan gambar artikel sesuai agar informasi dapat disampaikan dengan maksimal.
                </p>
            </div>
            
            <!-- Submit Buttons (Align Start) -->
            <div class="pt-[8px] flex items-start gap-[12px]">
                <!-- Simpan di Draft Button -->
                <div id="draft-button-wrapper">
                    <?php component('button', [
                        'text' => 'Simpan di Draft',
                        'variant' => '6',
                        'type' => 'button',
                        'id' => 'save-draft-btn',
                        'customPadding' => 'py-[12px] px-[24px]',
                        'class' => 'h-[52px]'
                    ]); ?>
                </div>
                
                <!-- Publish/Save Button - Dynamic based on mode -->
                <div id="publish-button-wrapper">
                    <?php component('button', [
                        'text' => 'Publish Artikel',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-publish-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
console.log('Modal script loaded');

// Track modal mode (create or edit)
let isEditMode = false;

// Define validateForm first before it's used
function validateForm() {
    console.log('validateForm called, isEditMode:', isEditMode);
    
    // Skip validation in edit mode - button always active
    if (isEditMode) {
        return;
    }
    
    // Create mode: validate author name and image
    const imageInput = document.getElementById('article-image-input');
    const authorName = document.getElementById('author-name').value.trim();
    const hasImage = imageInput ? imageInput.files.length > 0 : false;
    
    console.log('Validation - authorName:', authorName, 'hasImage:', hasImage);
    
    const allFilled = authorName && hasImage;
    const publishButtonWrapper = document.getElementById('publish-button-wrapper');
    
    if (!publishButtonWrapper) {
        console.error('publish-button-wrapper not found');
        return;
    }
    
    if (allFilled) {
        // Enable button - variant 1 (primary red)
        publishButtonWrapper.innerHTML = `
            <button type="submit" id="submit-publish-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                Publish Artikel
            </button>
        `.trim();
        console.log('Button enabled');
    } else {
        // Disable button - variant 10
        publishButtonWrapper.innerHTML = `
            <button type="submit" id="submit-publish-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                Publish Artikel
            </button>
        `;
        console.log('Button disabled');
    }
}

// Expose validateForm as global function for external calls
window.validatePublishForm = validateForm;

// Function to open publish article modal
window.openPublishArticleModal = function() {
    console.log('openPublishArticleModal called');
    
    try {
        const modal = document.getElementById('modal-publish-article');
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }
        
        const articleId = document.getElementById('article-id');
        
        // Detect mode: edit if article-id exists, create otherwise
        isEditMode = articleId && articleId.value;
        console.log('isEditMode:', isEditMode);
        
        // Update modal title and button based on mode
        const modalTitle = document.getElementById('modal-title');
        if (modalTitle) {
            modalTitle.textContent = isEditMode ? 'Simpan Perubahan' : 'Publish Artikel';
        }
        
        // Update button for edit mode (always active)
        if (isEditMode) {
            const publishButtonWrapper = document.getElementById('publish-button-wrapper');
            if (publishButtonWrapper) {
                publishButtonWrapper.innerHTML = `
                    <button type="submit" id="submit-publish-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                        Simpan Perubahan
                    </button>
                `.trim();
            }
        } else {
            // Create mode: validate before enabling button
            validateForm();
        }
        
        modal.classList.remove('hidden');
        console.log('Modal should be visible now');
    } catch (error) {
        console.error('Error opening modal:', error);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-publish-article');
    const closeBtn = document.getElementById('close-modal-publish-article');
    const imageInput = document.getElementById('article-image-input');
    const previewImage = document.getElementById('preview-image-article');
    const placeholderIcon = document.getElementById('placeholder-icon-article');
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            // Don't reset form in edit mode - keep existing data
        });
    }
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            // Don't reset form in edit mode - keep existing data
        }
    });
    
    // Function to show image preview
    window.showImagePreview = function(imageSrc) {
        if (previewImage && placeholderIcon && imageSrc) {
            previewImage.src = imageSrc;
            previewImage.onload = function() {
                previewImage.classList.remove('hidden');
                placeholderIcon.classList.add('hidden');
            };
            previewImage.onerror = function() {
                // Show placeholder if image fails to load
                previewImage.classList.add('hidden');
                placeholderIcon.classList.remove('hidden');
                console.warn('Failed to load image:', imageSrc);
            };
        }
    };
    
    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cautionText = document.getElementById('caution-text-article');
            
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
                    showToast('Ukuran file melebihi 2MB', 'error', 3000);
                    imageInput.value = '';
                    return;
                } else {
                    if (cautionText) {
                        cautionText.textContent = 'Maksimal ukuran file 2MB';
                        cautionText.style.color = '<?= colors("white_shadow") ?>';
                    }
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result);
                    // Trigger validation in create mode after image uploaded
                    if (!isEditMode) validateForm();
                };
                reader.onerror = function() {
                    showToast('Gagal membaca file gambar', 'error', 3000);
                    imageInput.value = '';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Add input listeners for form validation in create mode
    const authorInput = document.getElementById('author-name');
    if (authorInput) {
        authorInput.addEventListener('input', function() {
            if (!isEditMode) validateForm();
        });
    }
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (!isEditMode) validateForm();
        });
    }
    
    // Save as draft button
    const saveDraftBtn = document.getElementById('save-draft-btn');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            saveArticle('draft');
        });
    }
    
    // Form submit (Publish)
    const form = document.getElementById('form-publish-article');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveArticle('published');
        });
    }
    
    // Function to save article
    function saveArticle(status) {
        const title = document.getElementById('article-title').value.trim();
        const content = document.getElementById('article-content').value.trim();
        const authorName = document.getElementById('author-name').value.trim();
        const imageFile = imageInput.files[0];
        const articleId = document.getElementById('article-id') ? document.getElementById('article-id').value : null;
        
        // Validate required fields
        if (!title) {
            showToast('Judul artikel tidak boleh kosong', 'error', 3000);
            return;
        }
        
        if (!content) {
            showToast('Konten artikel tidak boleh kosong', 'error', 3000);
            return;
        }
        
        if (!authorName) {
            showToast('Nama penulis tidak boleh kosong', 'error', 3000);
            return;
        }
        
        // For publish, require image
        if (status === 'published' && !imageFile && !articleId) {
            showToast('Gambar artikel harus diupload untuk publish', 'error', 3000);
            return;
        }
        
        // Create FormData
        const formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        formData.append('author_name', authorName);
        formData.append('status', status);
        
        if (imageFile) {
            formData.append('featured_image', imageFile);
        }
        
        // Determine URL based on create or edit
        const url = articleId 
            ? '<?= url('/admin/articles') ?>/' + articleId + '/update'
            : '<?= url('/admin/articles/store') ?>';
        
        // Show loading state
        const submitBtn = document.getElementById('submit-publish-btn');
        const draftBtn = document.getElementById('save-draft-btn');
        if (submitBtn) submitBtn.disabled = true;
        if (draftBtn) draftBtn.disabled = true;
        
        // Send AJAX request
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success', 3000);
                
                // Close modal and redirect
                modal.classList.add('hidden');
                
                // Always redirect to articles list
                setTimeout(() => window.location.href = '<?= url('/admin/articles') ?>', 1000);
            } else {
                showToast(data.message, 'error', 3000);
                if (submitBtn) submitBtn.disabled = false;
                if (draftBtn) draftBtn.disabled = false;
            }
        })
        .catch(error => {
            showToast('Terjadi kesalahan saat menyimpan artikel', 'error', 3000);
            if (submitBtn) submitBtn.disabled = false;
            if (draftBtn) draftBtn.disabled = false;
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
        
        // Reset publish button to variant 10
        const publishButtonWrapper = document.getElementById('publish-button-wrapper');
        if (publishButtonWrapper) {
            publishButtonWrapper.innerHTML = `
                <button type="submit" id="submit-publish-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Publish Artikel
                </button>
            `.trim();
        }
    }
});
</script>
