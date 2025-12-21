<?php
/**
 * Modal Add FAQ Component
 * Modal for adding new FAQ
 */
?>

<!-- Modal Backdrop -->
<div id="modal-add-faq" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Tambah Pertanyaan dan Jawaban</h3>
            <button type="button" id="close-modal-add-faq" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Form Fields -->
        <form id="form-add-faq" class="space-y-[24px]">
            <!-- Pertanyaan -->
            <div>
                <label for="faq-question-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Pertanyaan
                </label>
                <input 
                    type="text" 
                    id="faq-question-add" 
                    name="faq_question"
                    placeholder="Isi pertanyaan untuk FAQ"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Jawaban -->
            <div>
                <label for="faq-answer-add" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Jawaban
                </label>
                <textarea 
                    id="faq-answer-add" 
                    name="faq_answer"
                    placeholder="Isi jawaban dari pertanyaan"
                    rows="6"
                    class="w-full px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors resize-none"
                ></textarea>
            </div>
            
            <!-- Action Button -->
            <div class="pt-[8px] flex justify-center">
                <!-- Submit Button -->
                <div id="submit-button-wrapper-add-faq">
                    <?php component('button', [
                        'text' => 'Tambah FAQ',
                        'variant' => '10',
                        'type' => 'submit',
                        'id' => 'submit-add-faq-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-add-faq');
    const closeBtn = document.getElementById('close-modal-add-faq');
    const openBtn = document.getElementById('btn-add-faq');
    
    // Open modal
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });
    }
    
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
        const faqQuestion = document.getElementById('faq-question-add').value.trim();
        const faqAnswer = document.getElementById('faq-answer-add').value.trim();
        
        const allFilled = faqQuestion && faqAnswer;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-add-faq');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-faq-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Tambah FAQ
                </button>
            `;
        } else {
            // Change to variant 10 (disabled)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-faq-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah FAQ
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['faq-question-add', 'faq-answer-add'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-add-faq');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prepare form data
            const formData = new FormData();
            formData.append('faq_question', document.getElementById('faq-question-add').value);
            formData.append('faq_answer', document.getElementById('faq-answer-add').value);
            
            // Send AJAX request
            fetch('/admin/settings/faqs/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('FAQ berhasil ditambahkan!', 'success', 3000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat menyimpan data', 'error', 5000);
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
        
        // Reset submit button to variant 10
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-add-faq');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-add-faq-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Tambah FAQ
                </button>
            `;
        }
    }
});
</script>
