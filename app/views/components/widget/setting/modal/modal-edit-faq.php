<?php
/**
 * Modal Edit FAQ Component
 * Modal for editing existing FAQ
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-faq" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Ubah Pertanyaan dan Jawaban</h3>
            <button type="button" id="close-modal-edit-faq" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-faq" class="space-y-[24px]">
            <!-- Hidden ID field -->
            <input type="hidden" id="faq-id-edit" name="faq_id">
            
            <!-- Pertanyaan -->
            <div>
                <label for="faq-question-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Pertanyaan
                </label>
                <input 
                    type="text" 
                    id="faq-question-edit" 
                    name="faq_question"
                    placeholder="Isi pertanyaan untuk FAQ"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Jawaban -->
            <div>
                <label for="faq-answer-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Jawaban
                </label>
                <textarea 
                    id="faq-answer-edit" 
                    name="faq_answer"
                    placeholder="Isi jawaban dari pertanyaan"
                    rows="6"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors resize-none"
                ></textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center gap-[12px]" style="margin-top: 32px;">
                <!-- Delete Button -->
                <div id="delete-faq-button-wrapper">
                    <?php component('button', [
                        'text' => 'Hapus FAQ',
                        'variant' => '13',
                        'type' => 'button',
                        'id' => 'delete-faq-btn',
                        'icon' => 'trash',
                        'iconPosition' => 'left'
                    ]); ?>
                </div>
                
                <!-- Update Button -->
                <div id="submit-button-wrapper-edit-faq">
                    <?php component('button', [
                        'text' => 'Ubah FAQ',
                        'variant' => '1',
                        'type' => 'submit',
                        'id' => 'submit-edit-faq-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-faq');
    const closeBtn = document.getElementById('close-modal-edit-faq');
    const deleteFaqBtn = document.getElementById('delete-faq-btn');
    
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
    
    // Form validation
    function validateForm() {
        const faqQuestion = document.getElementById('faq-question-edit').value.trim();
        const faqAnswer = document.getElementById('faq-answer-edit').value.trim();
        
        const allFilled = faqQuestion && faqAnswer;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-faq');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-faq-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah FAQ
                </button>
            `;
        } else {
            // Change to variant 10 (disabled)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-faq-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah FAQ
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['faq-question-edit', 'faq-answer-edit'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-edit-faq');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const faqId = document.getElementById('faq-id-edit').value;
            if (!faqId) {
                alert('FAQ ID tidak ditemukan');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('faq_question', document.getElementById('faq-question-edit').value);
            formData.append('faq_answer', document.getElementById('faq-answer-edit').value);
            
            // Send AJAX request
            fetch(`/admin/settings/faqs/${faqId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('FAQ berhasil diperbarui!', 'success', 3000);
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
    
    // Delete FAQ handler
    if (deleteFaqBtn) {
        deleteFaqBtn.addEventListener('click', function() {
            // Store active tab before delete to prevent glitch on reload
            localStorage.setItem('activeSettingsTabIndex', '4');
            
            const faqId = document.getElementById('faq-id-edit').value;
            const faqQuestion = document.getElementById('faq-question-edit').value;
            
            // Open delete confirmation modal
            if (typeof openModalDeleteFaq === 'function') {
                openModalDeleteFaq(
                    'Hapus FAQ',
                    `Apakah Anda yakin ingin menghapus FAQ "${faqQuestion}"? Tindakan ini tidak dapat dibatalkan.`,
                    function() {
                        // Send AJAX request
                        fetch(`/admin/settings/faqs/${faqId}/delete`, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modal.classList.add('hidden');
                                resetForm();
                                showToast('FAQ berhasil dihapus!', 'success', 3000);
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
        });
    }
    
    // Reset form
    function resetForm() {
        if (form) form.reset();
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-faq');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-faq-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah FAQ
                </button>
            `;
        }
    }
    
    // Function to open modal with data (to be called from outside)
    window.openEditFaqModal = function(faqData) {
        // Set FAQ ID
        document.getElementById('faq-id-edit').value = faqData.id || '';
        
        // Populate form with existing data
        document.getElementById('faq-question-edit').value = faqData.question || '';
        document.getElementById('faq-answer-edit').value = faqData.answer || '';
        
        // Validate form to enable submit button
        validateForm();
        
        // Show modal
        modal.classList.remove('hidden');
    };
});
</script>
