<?php
/**
 * Setting Register Tab Widget
 * Widget untuk tab pengaturan pendaftaran
 */
?>

<!-- Toast Notification -->
<?php component('toast'); ?>

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
            'value' => $whatsappNumber ?? ''
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
        ><?= $whatsappTemplate ?? '*PENDAFTARAN BARU - Zivana Montessori School*

*Nama Anak:* {childName}
*Nama Orang Tua:* {parentName}
*Nomor Telepon:* {phone}
{address}
{message}

Terima kasih telah mendaftar di Zivana Montessori School!' ?></textarea>

        <div class="mb-[24px]"></div>

        <!-- Placeholder yang Tersedia -->
        <label class="block font-normal text-[16px] text-black-soft mb-[8px]">
            Placeholder yang Tersedia
        </label>
        <div class="w-full border border-border-light rounded-[12px] px-[16px] py-[12px]">
            <p class="font-normal text-[14px] text-black-soft mb-2">• {childName} - Nama anak</p>
            <p class="font-normal text-[14px] text-black-soft mb-2">• {childAge} - Usia anak</p>
            <p class="font-normal text-[14px] text-black-soft mb-2">• {parentName} - Nama orang tua</p>
            <p class="font-normal text-[14px] text-black-soft mb-2">• {phone} - Nomor telepon</p>
            <p class="font-normal text-[14px] text-black-soft mb-2">• {address} - Alamat (opsional)</p>
            <p class="font-normal text-[14px] text-black-soft mb-2">• {message} - Pesan tambahan (opsional)</p>
            <p class="font-normal text-[14px] text-black-soft mt-4">Gunakan *teks* untuk bold di WhatsApp</p>
        </div>

        <div class="mb-[24px]"></div>

        <!-- Save Button -->
        <?php component('button', [
            'text' => 'Simpan Pengaturan',
            'variant' => '1',
            'type' => 'button',
            'id' => 'save-settings-btn'
        ]); ?>
    </div>
</div>

<!-- Preview Section -->
<div class="mt-[12px] grid grid-cols-2 gap-[12px]">
    <!-- Preview Pesan Card -->
    <div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
        <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Preview Pesan</h2>
        <p class="font-normal text-[14px] leading-[21px] text-black-soft mb-[20px]">Lihat bagaimana pesan akan tampil di WhatsApp</p>
        
        <!-- Form Fields -->
        <?php component('input', [
            'name' => 'preview_parent_name',
            'id' => 'preview-parent-name',
            'label' => 'Nama Orang Tua',
            'placeholder' => 'Isi nama orang tua',
            'value' => ''
        ]); ?>
        
        <?php component('input', [
            'name' => 'preview_child_name',
            'id' => 'preview-child-name',
            'label' => 'Nama Anak',
            'placeholder' => 'Isi nama anak',
            'value' => '',
            'class' => 'mt-[20px]'
        ]); ?>
        
        <?php component('input', [
            'name' => 'preview_child_age',
            'id' => 'preview-child-age',
            'label' => 'Usia Anak',
            'placeholder' => 'Isi usia anak',
            'value' => '',
            'class' => 'mt-[20px]'
        ]); ?>
        
        <?php component('input', [
            'name' => 'preview_address',
            'id' => 'preview-address',
            'label' => 'Alamat',
            'placeholder' => 'Isi alamat',
            'value' => '',
            'class' => 'mt-[20px]'
        ]); ?>
        
        <?php component('input', [
            'name' => 'preview_phone',
            'id' => 'preview-phone',
            'type' => 'tel',
            'label' => 'Nomor Telepon',
            'placeholder' => 'Isi nomor telepon',
            'value' => '',
            'class' => 'mt-[20px]'
        ]); ?>
    </div>

    <!-- Hasil Pesan Card -->
    <div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
        <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Hasil Pesan</h2>
        <p class="font-normal text-[14px] leading-[21px] text-black-soft mb-[20px]">Hasil pesan berdasarkan data preview di samping:</p>
        
        <!-- Message Result Card -->
        <div id="message-result" class="w-full border border-border-light rounded-[20px] rounded-br-[4px] px-[16px] py-[12px] mb-[20px]" style="background-color: #E1FFDB;">
            <p class="font-normal text-[16px] text-black-soft whitespace-pre-line" id="result-text">*PENDAFTARAN BARU - Zivana Montessori School*

*Nama Anak:* {childName}
*Nama Orang Tua:* {parentName}
*Nomor Telepon:* {phone}
{address}
{message}

Terima kasih telah mendaftar di Zivana Montessori School!</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const templateTextarea = document.getElementById('whatsapp-template');
    const whatsappNumber = document.getElementById('whatsapp-number');
    const resultText = document.getElementById('result-text');
    const testSendBtn = document.getElementById('test-send-wa');
    
    // Preview form inputs
    const previewInputs = {
        parentName: document.getElementById('preview-parent-name'),
        childName: document.getElementById('preview-child-name'),
        childAge: document.getElementById('preview-child-age'),
        address: document.getElementById('preview-address'),
        phone: document.getElementById('preview-phone')
    };
    
    // Function to update result message
    function updateResultMessage() {
        let template = templateTextarea.value;
        
        // Replace placeholders with input values
        template = template.replace(/{childName}/g, previewInputs.childName.value || '{childName}');
        template = template.replace(/{childAge}/g, previewInputs.childAge.value || '{childAge}');
        template = template.replace(/{parentName}/g, previewInputs.parentName.value || '{parentName}');
        template = template.replace(/{phone}/g, previewInputs.phone.value || '{phone}');
        
        // Handle optional address - remove line if empty
        if (previewInputs.address.value) {
            template = template.replace(/{address}/g, '*Alamat:* ' + previewInputs.address.value);
        } else {
            template = template.replace(/{address}/g, '');
        }
        
        // Handle optional message - remove line if empty
        template = template.replace(/{message}/g, '');
        
        // Update result display
        resultText.textContent = template;
    }
    
    // Add event listeners to all preview inputs
    Object.values(previewInputs).forEach(input => {
        input.addEventListener('input', updateResultMessage);
    });
    
    // Add event listener to template textarea
    templateTextarea.addEventListener('input', updateResultMessage);
    
    // Test send button
    testSendBtn.addEventListener('click', function() {
        const waNumber = whatsappNumber.value.trim();
        const message = resultText.textContent;
        
        // Validate WhatsApp number
        if (!waNumber) {
            showToast('Mohon isi Nomor WhatsApp Sekolah terlebih dahulu', 'error');
            return;
        }
        
        // Validate that at least some preview data is filled
        if (!previewInputs.childName.value && !previewInputs.parentName.value) {
            showToast('Mohon isi data preview untuk testing pesan', 'error');
            return;
        }
        
        // Encode message for URL
        const encodedMessage = encodeURIComponent(message);
        
        // Create WhatsApp URL
        const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;
        
        // Open WhatsApp in new tab
        window.open(waUrl, '_blank');
    });
    
    // Save settings button
    const saveSettingsBtn = document.getElementById('save-settings-btn');
    saveSettingsBtn.addEventListener('click', function() {
        const waNumber = whatsappNumber.value.trim();
        const template = templateTextarea.value;
        
        // Validate
        if (!waNumber) {
            showToast('Mohon isi Nomor WhatsApp Sekolah', 'error');
            return;
        }
        
        if (!template) {
            showToast('Mohon isi Template Pesan WhatsApp', 'error');
            return;
        }
        
        // Disable button during save
        saveSettingsBtn.disabled = true;
        saveSettingsBtn.textContent = 'Menyimpan...';
        
        // Send AJAX request
        fetch('<?= url('/admin/settings/registration/save') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'whatsapp_number': waNumber,
                'whatsapp_template': template
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Pengaturan berhasil disimpan', 'success');
            } else {
                showToast(data.message || 'Gagal menyimpan pengaturan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menyimpan pengaturan', 'error');
        })
        .finally(() => {
            // Re-enable button
            saveSettingsBtn.disabled = false;
            saveSettingsBtn.textContent = 'Simpan Pengaturan';
        });
    });
    
    // Initialize result message on page load
    updateResultMessage();
});
</script>
