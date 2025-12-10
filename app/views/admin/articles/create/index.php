<?php
/**
 * Create Article Page
 * Page for creating new article/news
 */

$pageTitle = 'Buat Artikel';
$currentPage = $currentPage ?? 'articles';
$hideSidebar = true; // Hide sidebar for clean article creation experience

// Start output buffering
ob_start();
?>

<!-- Header Section (without card wrapper) -->
<div class="flex items-center justify-between mt-[18px] mb-[18px]">
    <!-- Left: Back Button + Title -->
    <div class="flex items-center ml-6">
        <!-- Back Button with margin-right 20px -->
        <?php component('button', [
            'variant' => '14',
            'text' => 'Kembali',
            'type' => 'button',
            'attrs' => [
                'onclick' => 'window.location.href="' . url('/admin/articles') . '"'
            ]
        ]); ?>
        
        <!-- Title with margin-left 20px -->
        <h1 class="font-normal text-[20px] text-black-highlight ml-5">Buat Artikel</h1>
    </div>
    
    <!-- Right: Publish Button with margin-right 32px -->
    <div class="mr-8">
        <?php component('button', [
            'variant' => '1',
            'text' => 'Publish Artikel',
            'type' => 'button',
            'id' => 'btn-publish-article',
            'attrs' => [
                'onclick' => 'publishArticle()'
            ]
        ]); ?>
    </div>
</div>

<!-- Main Card Container for Article Creation -->
<div class="mx-32 mb-6">
    <div class="border border-border-soft rounded-2xl p-6 bg-white-neutral h-[800px] flex flex-col">
        <!-- Title Input Placeholder -->
        <div class="mb-8">
            <input 
                type="text" 
                id="article-title" 
                placeholder="Judul Artikel..." 
                class="w-full font-normal text-[32px] text-black-soft placeholder-white-soft bg-transparent border-none outline-none focus:ring-0"
            />
        </div>
        
        <!-- Content Editor Placeholder -->
        <div class="flex-1 overflow-y-auto mb-5">
            <textarea 
                id="article-content" 
                placeholder="Mulai menulis artikel..." 
                class="w-full h-full font-normal text-[16px] text-black-soft placeholder:font-normal placeholder:text-[16px] placeholder-white-soft bg-transparent border-none outline-none focus:ring-0 resize-none"
            ></textarea>
        </div>
    </div>
</div>

<script>
function publishArticle() {
    const title = document.getElementById('article-title').value.trim();
    const content = document.getElementById('article-content').value.trim();
    
    if (!title) {
        showToast('Judul artikel tidak boleh kosong', 'error', 3000);
        return;
    }
    
    if (!content) {
        showToast('Konten artikel tidak boleh kosong', 'error', 3000);
        return;
    }
    
    // TODO: Implement article publish functionality
    showToast('Fungsi publish artikel akan segera diimplementasikan', 'error', 3000);
}
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
