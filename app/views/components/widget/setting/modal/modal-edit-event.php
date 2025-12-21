<?php
/**
 * Modal Edit Event Component
 * Modal for editing existing event
 */
?>

<!-- Modal Backdrop -->
<div id="modal-edit-event" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-[621px] px-[24px] py-[20px]">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[100%] text-black-soft">Edit Event</h3>
            <button type="button" id="close-modal-edit-event" class="text-black-highlight hover:text-black-soft transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Form Fields -->
        <form id="form-edit-event" class="space-y-[24px]">
            <!-- Hidden ID field -->
            <input type="hidden" id="event-id-edit" name="event_id">
            
            <!-- Tanggal and Rentang Waktu -->
            <div class="grid grid-cols-2 gap-[20px]">
                <!-- Tanggal -->
                <div>
                    <label for="event-date-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Tanggal
                    </label>
                    <div class="relative">
                        <input 
                            type="date" 
                            id="event-date-edit" 
                            name="event_date"
                            placeholder="Pilih tanggal event"
                            class="w-full h-[52px] px-[24px] pr-[48px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                        >
                        <span class="absolute right-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.6667 1.33333V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.33333 1.33333V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 6.66667H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <!-- Rentang Waktu -->
                <div>
                    <label for="event-time-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                        Rentang Waktu
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="event-time-edit" 
                            name="event_time"
                            placeholder="Pilih rentang waktu event"
                            class="w-full h-[52px] px-[24px] pr-[48px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                        >
                        <span class="absolute right-[16px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 14.6667C11.6819 14.6667 14.6667 11.6819 14.6667 8C14.6667 4.3181 11.6819 1.33333 8 1.33333C4.3181 1.33333 1.33333 4.3181 1.33333 8C1.33333 11.6819 4.3181 14.6667 8 14.6667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 4V8L10.6667 9.33333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Nama Event -->
            <div>
                <label for="event-name-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Nama Event
                </label>
                <input 
                    type="text" 
                    id="event-name-edit" 
                    name="event_name"
                    placeholder="Isi nama event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Tempat Event -->
            <div>
                <label for="event-place-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    Tempat Event
                </label>
                <input 
                    type="text" 
                    id="event-place-edit" 
                    name="event_place"
                    placeholder="Isi tempat event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- URL Informasi Event -->
            <div>
                <label for="event-url-edit" class="block font-normal text-[12px] leading-[21px] text-black-highlight mb-[8px]">
                    URL Informasi Event
                </label>
                <input 
                    type="url" 
                    id="event-url-edit" 
                    name="event_url"
                    placeholder="Isi URL informasi event"
                    class="w-full h-[52px] px-[24px] py-[12px] bg-white-neutral border border-border-light rounded-xl text-[16px] leading-[28px] text-black-soft placeholder:text-white-shadow focus:outline-none focus:border-primary transition-colors"
                >
            </div>
            
            <!-- Event Terbuka untuk Umum -->
            <div>
                <label class="flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        id="is-public-edit" 
                        name="is_public"
                        class="w-3 h-3 border-gray-300 rounded"
                        style="margin-right: 8px; accent-color: <?= colors('primary') ?>;"
                    >
                    <span class="font-normal text-[14px] leading-[21px] text-black-soft">
                        Event terbuka untuk umum
                    </span>
                </label>
            </div>
            
            <!-- Action Buttons -->
            <div class="pt-[8px] flex items-center justify-center gap-[12px]" style="margin-top: 32px;">
                <!-- Delete Button -->
                <div id="delete-event-button-wrapper">
                    <?php component('button', [
                        'text' => 'Hapus Event',
                        'variant' => '13',
                        'type' => 'button',
                        'id' => 'delete-event-btn',
                        'icon' => 'trash',
                        'iconPosition' => 'left'
                    ]); ?>
                </div>
                
                <!-- Update Button -->
                <div id="submit-button-wrapper-edit-event">
                    <?php component('button', [
                        'text' => 'Ubah Event',
                        'variant' => '1',
                        'type' => 'submit',
                        'id' => 'submit-edit-event-btn'
                    ]); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-event');
    const closeBtn = document.getElementById('close-modal-edit-event');
    const deleteEventBtn = document.getElementById('delete-event-btn');
    
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
        const eventDate = document.getElementById('event-date-edit').value.trim();
        const eventTime = document.getElementById('event-time-edit').value.trim();
        const eventName = document.getElementById('event-name-edit').value.trim();
        const eventPlace = document.getElementById('event-place-edit').value.trim();
        const eventUrl = document.getElementById('event-url-edit').value.trim();
        
        const allFilled = eventDate && eventTime && eventName && eventPlace && eventUrl;
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-event');
        
        if (allFilled) {
            // Change to variant 1 (primary)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-event-btn" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-6 rounded-xl bg-primary text-white font-bold text-base h-[52px] hover:opacity-90 active:scale-95 cursor-pointer">
                    Ubah Event
                </button>
            `;
        } else {
            // Change to variant 10 (disabled)
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-event-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Event
                </button>
            `;
        }
    }
    
    // Add input listeners for form validation
    const formInputs = ['event-date-edit', 'event-time-edit', 'event-name-edit', 'event-place-edit', 'event-url-edit'];
    formInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', validateForm);
        }
    });
    
    // Form submit
    const form = document.getElementById('form-edit-event');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const eventId = document.getElementById('event-id-edit').value;
            if (!eventId) {
                alert('Event ID tidak ditemukan');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('event_date', document.getElementById('event-date-edit').value);
            formData.append('event_time', document.getElementById('event-time-edit').value);
            formData.append('event_name', document.getElementById('event-name-edit').value);
            formData.append('event_place', document.getElementById('event-place-edit').value);
            formData.append('event_url', document.getElementById('event-url-edit').value);
            
            // Add is_public checkbox value
            const isPublicCheckbox = document.getElementById('is-public-edit');
            if (isPublicCheckbox && isPublicCheckbox.checked) {
                formData.append('is_public', '1');
            }
            
            // Send AJAX request
            fetch(`/admin/settings/events/${eventId}/update`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    resetForm();
                    showToast('Event berhasil diperbarui!', 'success', 3000);
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
    
    // Delete event handler
    if (deleteEventBtn) {
        deleteEventBtn.addEventListener('click', function() {
            // Store active tab before delete to prevent glitch on reload
            localStorage.setItem('activeSettingsTabIndex', '3');
            
            const eventId = document.getElementById('event-id-edit').value;
            const eventName = document.getElementById('event-name-edit').value;
            
            // Open delete confirmation modal
            if (typeof openModalDeleteEvent === 'function') {
                openModalDeleteEvent(
                    'Hapus Event',
                    `Apakah Anda yakin ingin menghapus event "${eventName}"? Tindakan ini tidak dapat dibatalkan.`,
                    function() {
                        // Send AJAX request
                        fetch(`/admin/settings/events/${eventId}/delete`, {
                            method: 'POST'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modal.classList.add('hidden');
                                resetForm();
                                showToast('Event berhasil dihapus!', 'success', 3000);
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
        const submitButtonWrapper = document.getElementById('submit-button-wrapper-edit-event');
        if (submitButtonWrapper) {
            submitButtonWrapper.innerHTML = `
                <button type="submit" id="submit-edit-event-btn" disabled class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-[12px] px-[24px] rounded-xl bg-white-secondary text-white-shadow font-normal text-base leading-[28px] border border-border-light h-[52px] opacity-50 cursor-not-allowed">
                    Ubah Event
                </button>
            `;
        }
    }
    
    // Function to open modal with data (to be called from outside)
    window.openEditEventModal = function(eventData) {
        // Set event ID
        document.getElementById('event-id-edit').value = eventData.id || '';
        
        // Populate form with existing data
        document.getElementById('event-date-edit').value = eventData.date || '';
        document.getElementById('event-time-edit').value = eventData.time || '';
        document.getElementById('event-name-edit').value = eventData.name || '';
        document.getElementById('event-place-edit').value = eventData.place || '';
        document.getElementById('event-url-edit').value = eventData.url || '';
        
        // Set checkbox state
        const isPublicCheckbox = document.getElementById('is-public-edit');
        if (isPublicCheckbox) {
            isPublicCheckbox.checked = eventData.is_public === true || eventData.is_public === 1;
        }
        
        // Validate form to enable submit button
        validateForm();
        
        // Show modal
        modal.classList.remove('hidden');
    };
});
</script>
