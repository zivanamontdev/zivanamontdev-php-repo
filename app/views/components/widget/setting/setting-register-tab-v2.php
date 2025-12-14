<?php
/**
 * Setting Register Tab Widget - Dynamic Version
 * Widget untuk tab pengaturan pendaftaran dengan dynamic form builder
 */

// Get data from controller
$fields = $fields ?? [];
$whatsappNumber = $whatsappNumber ?? '';
$whatsappTemplate = $whatsappTemplate ?? '';
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

<!-- Delete Confirmation Modal -->
<?php component('widget/modal-delete-confirmation', ['modalId' => 'modal-delete-field']); ?>

<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Card Header -->
    <div class="mb-6">
        <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Pengaturan Pendaftaran</h2>
        <p class="font-normal text-[14px] leading-[21px] text-black-soft">Atur nomor WhatsApp dan template pesan untuk form pendaftaran</p>
    </div>

    <!-- Form Content -->
    <div>
        <!-- Nomor WhatsApp Sekolah -->
        <label for="whatsapp-number" class="block font-normal text-[16px] text-black-soft mb-[8px]">
            Nomor WhatsApp Sekolah
        </label>
        <?php component('input', [
            'name' => 'whatsapp_number',
            'id' => 'whatsapp-number',
            'type' => 'tel',
            'placeholder' => '628123456789',
            'icon' => 'phone',
            'value' => $whatsappNumber
        ]); ?>
        <p class="font-normal text-[12px] text-black-soft mt-[8px]">Format: 62xxx (Tanpa tanda + atau spasi)</p>

        <div class="mb-[24px]"></div>

        <!-- Template Pesan WhatsApp -->
        <label for="whatsapp-template" class="block font-normal text-[16px] text-black-soft mb-[8px]">
            Template Pesan WhatsApp
        </label>
        <textarea 
            id="whatsapp-template" 
            name="whatsapp_template" 
            rows="10"
            class="w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] leading-[28px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors px-[16px] py-[12px] resize-none"
        ><?= e($whatsappTemplate) ?></textarea>

        <div class="mb-[24px]"></div>

        <!-- Placeholder yang Tersedia -->
        <label class="block font-normal text-[16px] text-black-soft mb-[8px]">
            Placeholder yang Tersedia
        </label>
        <div id="placeholder-list" class="w-full border border-border-light rounded-[12px] px-[16px] py-[12px]">
            <?php foreach ($fields as $field): ?>
                <p class="font-normal text-[14px] text-black-soft mb-2">• {<?= e($field['field_name']) ?>} - <?= e($field['field_label']) ?></p>
            <?php endforeach; ?>
            <p class="font-normal text-[14px] text-black-soft mt-4">Gunakan *teks* untuk bold di WhatsApp</p>
        </div>
    </div>
</div>

<!-- Preview Section -->
<div class="mt-[12px] grid grid-cols-2 gap-[12px]">
    <!-- Preview Pesan Card -->
    <div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-[4px]">
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft">Preview Pesan</h2>
            <?php component('button', [
                'text' => 'Tambah Field',
                'variant' => '8',
                'type' => 'button',
                'icon' => 'plus',
                'id' => 'btn-add-field'
            ]); ?>
        </div>
        <p class="font-normal text-[14px] leading-[21px] text-black-soft mb-[20px]">Lihat bagaimana pesan akan tampil di WhatsApp</p>
        
        <!-- Sortable Form Fields -->
        <div id="sortable-fields" class="space-y-[16px] mb-[20px]">
            <?php foreach ($fields as $field): ?>
                <div class="field-item bg-white-secondary border border-border-light rounded-[12px] p-4" data-id="<?= $field['id'] ?>">
                    <!-- Field Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="drag-handle w-5 h-5 text-white-soft cursor-move" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                            <span class="font-medium text-[14px] text-black-soft"><?= e($field['field_label']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php component('button', [
                                'variant' => '9',
                                'icon' => 'edit',
                                'type' => 'button',
                                'class' => 'btn-edit-field',
                                'attrs' => ['data-id' => $field['id']]
                            ]); ?>
                            <?php component('button', [
                                'variant' => '9',
                                'icon' => 'trash',
                                'type' => 'button',
                                'class' => 'btn-delete-field',
                                'attrs' => ['data-id' => $field['id']]
                            ]); ?>
                        </div>
                    </div>
                    
                    <!-- Field Input -->
                    <?php component('input', [
                        'name' => 'preview_' . $field['field_name'],
                        'id' => 'preview-' . $field['field_name'],
                        'type' => $field['field_type'],
                        'placeholder' => $field['placeholder_text'],
                        'value' => '',
                        'class' => 'preview-input',
                        'attrs' => ['data-field-name' => $field['field_name']]
                    ]); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Save Button -->
        <?php component('button', [
            'text' => 'Simpan Perubahan',
            'variant' => '1',
            'type' => 'button',
            'id' => 'btn-save-fields'
        ]); ?>
    </div>

    <!-- Hasil Pesan Card -->
    <div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
        <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Hasil Pesan</h2>
        <p class="font-normal text-[14px] leading-[21px] text-black-soft mb-[20px]">Hasil pesan berdasarkan data preview di samping:</p>
        
        <!-- Message Result Card -->
        <div id="message-result" class="w-full border border-border-light rounded-[20px] rounded-br-[4px] px-[16px] py-[12px] mb-[20px]" style="background-color: #E1FFDB;">
            <p class="font-normal text-[16px] text-black-soft whitespace-pre-line" id="result-text"><?= e($whatsappTemplate) ?></p>
        </div>
        
        <!-- Test Send Button -->
        <?php component('button', [
            'text' => 'Tes Kirim ke WhatsApp',
            'variant' => '19',
            'type' => 'button',
            'id' => 'test-send-wa'
        ]); ?>
    </div>
</div>

<!-- Modal Add/Edit Field -->
<div id="field-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white-neutral rounded-[12px] p-8 w-full max-w-md">
        <h3 class="font-bold text-[18px] text-black-soft mb-6" id="modal-title">Tambah Field Baru</h3>
        
        <form id="field-form">
            <input type="hidden" id="field-id" name="id">
            
            <?php component('input', [
                'name' => 'field_label',
                'id' => 'field-label',
                'label' => 'Label Field',
                'placeholder' => 'Contoh: Nama Orang Tua',
                'required' => true
            ]); ?>
            
            <?php component('input', [
                'name' => 'field_name',
                'id' => 'field-name',
                'label' => 'Nama Placeholder',
                'placeholder' => 'Contoh: parentName (tanpa spasi)',
                'required' => true,
                'class' => 'mt-[20px]'
            ]); ?>
            
            <div class="mt-[20px]">
                <label class="block font-normal text-[16px] text-black-soft mb-[8px]">Tipe Field</label>
                <select id="field-type" name="field_type" class="w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] text-black-soft px-[16px] py-[12px]">
                    <option value="text">Text</option>
                    <option value="tel">Telephone</option>
                    <option value="number">Number</option>
                    <option value="email">Email</option>
                    <option value="textarea">Textarea</option>
                </select>
            </div>
            
            <?php component('input', [
                'name' => 'placeholder_text',
                'id' => 'field-placeholder',
                'label' => 'Placeholder Text',
                'placeholder' => 'Contoh: Isi nama orang tua',
                'class' => 'mt-[20px]'
            ]); ?>
            
            <div class="mt-[20px] flex items-center gap-2">
                <input type="checkbox" id="field-required" name="is_required" class="w-4 h-4">
                <label for="field-required" class="font-normal text-[16px] text-black-soft">Field Wajib Diisi</label>
            </div>
            
            <div class="mt-6 flex gap-3">
                <?php component('button', [
                    'text' => 'Batal',
                    'variant' => '6',
                    'type' => 'button',
                    'id' => 'btn-cancel-modal',
                    'class' => 'flex-1'
                ]); ?>
                <?php component('button', [
                    'text' => 'Simpan',
                    'variant' => '1',
                    'type' => 'submit',
                    'id' => 'btn-submit-field',
                    'class' => 'flex-1'
                ]); ?>
            </div>
        </form>
    </div>
</div>

<!-- SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let isEditMode = false;
    
    // Elements
    const whatsappNumber = document.getElementById('whatsapp-number');
    const templateTextarea = document.getElementById('whatsapp-template');
    const resultText = document.getElementById('result-text');
    const testSendBtn = document.getElementById('test-send-wa');
    const fieldModal = document.getElementById('field-modal');
    const fieldForm = document.getElementById('field-form');
    const modalTitle = document.getElementById('modal-title');
    
    // Track changes untuk update preview
    templateTextarea.addEventListener('input', () => {
        updateResultMessage();
    });
    
    // Initialize Sortable
    const sortableFields = document.getElementById('sortable-fields');
    const sortable = Sortable.create(sortableFields, {
        animation: 150,
        handle: '.drag-handle',
        onEnd: function() {
            // Auto save order on drag
            updateFieldOrder();
        }
    });
    
    // Update result message
    function updateResultMessage() {
        let template = templateTextarea.value;
        
        // Get all preview inputs
        const previewInputs = document.querySelectorAll('.preview-input');
        previewInputs.forEach(input => {
            const fieldName = input.dataset.fieldName;
            const value = input.value || `{${fieldName}}`;
            template = template.replace(new RegExp(`\\{${fieldName}\\}`, 'g'), value);
        });
        
        resultText.textContent = template;
    }
    
    // Add event listeners to preview inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('preview-input')) {
            updateResultMessage();
        }
    });
    
    // Save settings (WhatsApp number and template)
    document.getElementById('btn-save-fields').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('whatsapp_number', whatsappNumber.value);
        formData.append('whatsapp_template', templateTextarea.value);
        
        fetch('<?= url('/admin/settings/registration/save') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Pengaturan berhasil disimpan', 'success');
                // Auto generate template and reload
                loadFieldsAndGenerate();
            } else {
                showToast('Gagal menyimpan: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menyimpan', 'error');
        });
    });
    
    // Add field button
    document.getElementById('btn-add-field').addEventListener('click', function() {
        isEditMode = false;
        modalTitle.textContent = 'Tambah Field Baru';
        fieldForm.reset();
        document.getElementById('field-id').value = '';
        fieldModal.classList.remove('hidden');
        fieldModal.classList.add('flex');
    });
    
    // Edit field buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-edit-field')) {
            const btn = e.target.closest('.btn-edit-field');
            const fieldId = btn.dataset.id;
            editField(fieldId);
        }
    });
    
    // Delete field buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-field')) {
            const btn = e.target.closest('.btn-delete-field');
            const fieldId = btn.dataset.id;
            
            // Get field name from DOM
            const fieldItem = btn.closest('.field-item');
            const fieldLabel = fieldItem.querySelector('span').textContent;
            
            // Open delete confirmation modal
            openModalDeleteField(
                'Hapus Field',
                `Apakah Anda yakin ingin menghapus field "${fieldLabel}"? Tindakan ini tidak dapat dibatalkan.`,
                function() {
                    deleteField(fieldId);
                }
            );
        }
    });
    
    // Cancel modal
    document.getElementById('btn-cancel-modal').addEventListener('click', function() {
        fieldModal.classList.add('hidden');
        fieldModal.classList.remove('flex');
    });
    
    // Submit field form
    fieldForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(fieldForm);
        const url = isEditMode ? '<?= url('/admin/settings/fields/update') ?>' : '<?= url('/admin/settings/fields/create') ?>';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                fieldModal.classList.add('hidden');
                fieldModal.classList.remove('flex');
                // Auto-generate template after create/edit to reflect new labels
                loadFieldsAndGenerate();
            } else {
                showToast('Gagal: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    });
    
    // Edit field
    function editField(id) {
        // Fetch field data from server
        fetch(`<?= url('/admin/settings/fields/get') ?>`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const field = data.data.find(f => f.id == id);
                if (field) {
                    isEditMode = true;
                    modalTitle.textContent = 'Edit Field';
                    document.getElementById('field-id').value = field.id;
                    document.getElementById('field-label').value = field.field_label;
                    document.getElementById('field-name').value = field.field_name;
                    document.getElementById('field-type').value = field.field_type;
                    document.getElementById('field-placeholder').value = field.placeholder_text || '';
                    document.getElementById('field-required').checked = field.is_required == 1;
                    
                    fieldModal.classList.remove('hidden');
                    fieldModal.classList.add('flex');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal mengambil data field', 'error');
        });
    }
    
    // Delete field
    function deleteField(id) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('<?= url('/admin/settings/fields/delete') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Auto-generate template after delete
                loadFieldsAndGenerate();
            } else {
                showToast('Gagal: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }
    
    // Update field order
    function updateFieldOrder() {
        const orders = {};
        const items = sortableFields.querySelectorAll('.field-item');
        items.forEach((item, index) => {
            orders[item.dataset.id] = index + 1;
        });
        
        const formData = new FormData();
        formData.append('orders', JSON.stringify(orders));
        
        fetch('<?= url('/admin/settings/fields/update-order') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload fields with new order and regenerate template
                loadFieldsAndGenerate();
            } else {
                console.error('Failed to update order:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Load fields (reload page data)
    function loadFields() {
        fetch('<?= url('/admin/settings/fields/get') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fields = data.data;
                
                // Update placeholder list
                updatePlaceholderList(fields);
                
                // Update preview fields
                updatePreviewFields(fields);
                
                // Update result message
                updateResultMessage();
            }
        })
        .catch(error => {
            console.error('Error loading fields:', error);
            location.reload(); // Fallback to full reload
        });
    }
    
    // Load fields and auto-generate template
    function loadFieldsAndGenerate() {
        fetch('<?= url('/admin/settings/fields/get') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fields = data.data;
                
                // Update placeholder list
                updatePlaceholderList(fields);
                
                // Update preview fields
                updatePreviewFields(fields);
                
                // Auto-generate template
                autoGenerateTemplate(fields);
                
                // Update result message
                updateResultMessage();
            }
        })
        .catch(error => {
            console.error('Error loading fields:', error);
            location.reload();
        });
    }
    
    // Update placeholder list
    function updatePlaceholderList(fields) {
        const placeholderList = document.getElementById('placeholder-list');
        let html = '';
        
        fields.forEach(field => {
            html += `<p class="font-normal text-[14px] text-black-soft mb-2">• {${field.field_name}} - ${field.field_label}</p>`;
        });
        
        html += '<p class="font-normal text-[14px] text-black-soft mt-4">Gunakan *teks* untuk bold di WhatsApp</p>';
        
        placeholderList.innerHTML = html;
    }
    
    // Update preview fields
    function updatePreviewFields(fields) {
        const sortableFields = document.getElementById('sortable-fields');
        let html = '';
        
        fields.forEach(field => {
            html += `
                <div class="field-item bg-white-secondary border border-border-light rounded-[12px] p-4" data-id="${field.id}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="drag-handle w-5 h-5 text-white-soft cursor-move" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                            <span class="font-medium text-[14px] text-black-soft">${field.field_label}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 p-[10px] rounded-xl bg-white-secondary text-black-highlight hover:opacity-80 active:scale-95 cursor-pointer btn-edit-field" data-id="${field.id}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button type="button" class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 p-[10px] rounded-xl bg-white-secondary text-black-highlight hover:opacity-80 active:scale-95 cursor-pointer btn-delete-field" data-id="${field.id}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <input type="${field.field_type}" id="preview-${field.field_name}" name="preview_${field.field_name}" placeholder="${field.placeholder_text || ''}" value="" class="preview-input w-full bg-white-neutral border border-[#E0E0E0] rounded-[12px] font-normal text-[16px] leading-[28px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors px-[16px] py-[12px]" data-field-name="${field.field_name}">
                    </div>
                </div>
            `;
        });
        
        sortableFields.innerHTML = html;
        
        // Reinitialize Sortable
        Sortable.create(sortableFields, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function() {
                showSaveButton();
                updateFieldOrder();
            }
        });
    }
    
    // Auto-generate template from fields
    function autoGenerateTemplate(fields) {
        let template = '*PENDAFTARAN BARU - Zivana Montessori School*\n\n';
        
        fields.forEach(field => {
            template += `*${field.field_label}:* {${field.field_name}}\n`;
        });
        
        template += '\nTerima kasih telah mendaftar di Zivana Montessori School!';
        
        templateTextarea.value = template;
        
        // Auto-save template after generation
        const formData = new FormData();
        formData.append('whatsapp_number', whatsappNumber.value);
        formData.append('whatsapp_template', template);
        
        fetch('<?= url('/admin/settings/registration/save') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Template auto-saved');
            }
        })
        .catch(error => {
            console.error('Error auto-saving template:', error);
        });
    }
    
    // Test send WhatsApp
    testSendBtn.addEventListener('click', function() {
        const waNumber = whatsappNumber.value.trim();
        const message = resultText.textContent;
        
        if (!waNumber) {
            showToast('Mohon isi Nomor WhatsApp Sekolah terlebih dahulu', 'error');
            return;
        }
        
        const encodedMessage = encodeURIComponent(message);
        const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;
        window.open(waUrl, '_blank');
    });
    
    // Initialize
    updateResultMessage();
});
</script>
