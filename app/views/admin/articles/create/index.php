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
            'variant' => '15',
            'text' => 'Publish Artikel',
            'type' => 'button',
            'id' => 'btn-publish-article',
            'attrs' => [
                'onclick' => 'openPublishArticleModal()'
            ]
        ]); ?>
    </div>
</div>

<!-- Publish Article Modal -->
<?php component('widget/articles/modals/modal-publish-article'); ?>

<!-- Main Card Container for Article Creation -->
<div class="mx-32 mb-6">
    <?php component('widget/articles/article-rich-editor', [
        'part' => 'toolbar'
    ]); ?>

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
        
        <!-- Content Editor -->
        <?php component('widget/articles/article-rich-editor'); ?>
    </div>
</div>

<script>
// Validation helper function (can be used if needed)
function validateArticleForm() {
    if (typeof window.syncArticleEditorContent === 'function') {
        window.syncArticleEditorContent();
    }

    const title = document.getElementById('article-title').value.trim();
    const content = document.getElementById('article-content').value.trim();
    
    if (!title) {
        showToast('Judul artikel tidak boleh kosong', 'error', 3000);
        return false;
    }
    
    if (!content) {
        showToast('Konten artikel tidak boleh kosong', 'error', 3000);
        return false;
    }
    
    return true;
}
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
