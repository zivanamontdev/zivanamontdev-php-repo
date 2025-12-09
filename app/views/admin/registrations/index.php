<?php 
$pageTitle = 'Riwayat Pendaftar';
$currentPage = 'registrations';
ob_start(); 
?>

<!-- Admin Navbar Component -->
<?php component('admin-navbar', ['title' => 'Riwayat Pendaftar']); ?>

<!-- Data Pendaftar Card -->
<div class="bg-white-neutral border border-border-soft rounded-[12px] p-8">
    <!-- Header with Title, Description and Search -->
    <div class="flex items-center justify-between mb-[16px]">
        <!-- Title and Description -->
        <div>
            <h2 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[4px]">Data Pendaftar</h2>
            <p class="font-normal text-[14px] leading-[21px] text-black-soft">Data isian pendaftar pada website</p>
        </div>
        
        <!-- Search Input -->
        <div class="relative w-[264px]">
            <span class="absolute left-[12px] top-1/2 -translate-y-1/2 w-4 h-4 text-white-soft pointer-events-none">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <input 
                type="text" 
                name="search"
                placeholder="Cari Pendaftar"
                value="<?= e($search ?? '') ?>"
                class="w-full pl-[36px] pr-[12px] py-[10px] bg-white-neutral border border-[#E0E0E0] rounded-[12px] text-[12px] text-black-soft placeholder:text-white-soft focus:outline-none focus:border-primary transition-colors"
            >
        </div>
    </div>

    <!-- Table Header -->
    <div class="bg-white-secondary px-[16px] py-[12px] rounded-[12px] mb-[12px]">
        <div class="grid grid-cols-[1.3fr_1.5fr_1.5fr_0.8fr_2.5fr_1fr_40px] gap-4">
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Waktu Pendaftar</span>
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Nama Orang Tua</span>
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Nama Anak</span>
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Umur Anak</span>
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Alamat</span>
            <span class="font-medium text-[12px] leading-[100%] text-black-neutral">Nomor WhatsApp</span>
            <span></span>
        </div>
    </div>

    <!-- Data List -->
    <div class="divide-y divide-border-soft px-[16px]">
        <?php if (!empty($registrations)): ?>
            <?php foreach ($registrations as $reg): ?>
            <div class="grid grid-cols-[1.3fr_1.5fr_1.5fr_0.8fr_2.5fr_1fr_40px] gap-4 py-[12px] items-center">
                <span class="text-[12px] text-black-soft"><?= isset($reg['created_at']) ? date('d M Y H:i', strtotime($reg['created_at'])) : '-' ?></span>
                <span class="text-[12px] text-black-soft"><?= e($reg['parent_name'] ?? '-') ?></span>
                <span class="text-[12px] text-black-soft"><?= e($reg['child_name'] ?? '-') ?></span>
                <span class="text-[12px] text-black-soft"><?= e($reg['child_age'] ?? '-') ?></span>
                <span class="text-[12px] text-black-soft truncate"><?= e($reg['address'] ?? '-') ?></span>
                <span class="text-[12px] text-black-soft"><?= e($reg['whatsapp'] ?? '-') ?></span>
                <button type="button" 
                        class="w-[24px] h-[24px] flex items-center justify-center text-white-soft hover:text-black-soft transition-colors cursor-pointer"
                        onclick="copyToClipboard('<?= e($reg['whatsapp'] ?? '') ?>')"
                        title="Copy WhatsApp">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5.5" y="5.5" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M5 10.5H3.5C2.94772 10.5 2.5 10.0523 2.5 9.5V3.5C2.5 2.94772 2.94772 2.5 3.5 2.5H9.5C10.0523 2.5 10.5 2.94772 10.5 3.5V5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="py-[24px] text-center text-[14px] text-white-soft">
                Belum ada data pendaftar
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination -->
<?php if (($totalPages ?? 0) > 1): ?>
<div class="mt-6 flex items-center justify-between">
    <p class="text-[12px] text-white-soft">
        Menampilkan <?= ((int)($currentPage ?? 1) - 1) * 10 + 1 ?> - <?= min((int)($currentPage ?? 1) * 10, $total) ?> dari <?= $total ?> data
    </p>
    <div class="flex items-center gap-2">
        <?php if ((int)($currentPage ?? 1) > 1): ?>
        <a href="?page=<?= (int)($currentPage ?? 1) - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
            class="px-3 py-2 text-[12px] text-black-highlight bg-white-neutral border border-border-soft rounded-[8px] hover:bg-white-secondary transition-colors">
            Sebelumnya
        </a>
        <?php endif; ?>
        
        <?php if ((int)($currentPage ?? 1) < $totalPages): ?>
        <a href="?page=<?= (int)($currentPage ?? 1) + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
            class="px-3 py-2 text-[12px] text-white-neutral bg-primary rounded-[8px] hover:bg-opacity-90 transition-colors">
            Selanjutnya
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const value = this.value;
                window.location.href = '?search=' + encodeURIComponent(value);
            }, 500);
        });
    }
});

// Copy to clipboard function
function copyToClipboard(text) {
    if (navigator.clipboard && text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show toast or feedback
            if (typeof showToast === 'function') {
                showToast('Nomor WhatsApp berhasil disalin', 'success', 2000);
            } else {
                alert('Nomor WhatsApp berhasil disalin');
            }
        }).catch(function(err) {
            console.error('Gagal menyalin: ', err);
        });
    }
}
</script>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>