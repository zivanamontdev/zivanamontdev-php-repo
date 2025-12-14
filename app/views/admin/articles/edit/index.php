<?php
/**
 * Edit Article Page
 * Page for editing existing article/news
 */

$pageTitle = 'Edit Artikel';
$currentPage = $currentPage ?? 'articles';
$hideSidebar = true; // Hide sidebar for clean article editing experience

// Get article data from controller
$article = $article ?? null;

// If no article data, redirect back to articles list
if (!$article) {
    header('Location: ' . url('/admin/articles'));
    exit;
}

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
        <h1 class="font-normal text-[20px] text-black-highlight ml-5">Edit Artikel</h1>
    </div>
    
    <!-- Right: Delete and Publish Buttons with margin-right 32px -->
    <div class="flex items-center gap-3 mr-8">
        <!-- Delete Button -->
        <?php component('button', [
            'variant' => '18',
            'text' => 'Hapus',
            'type' => 'button',
            'id' => 'btn-delete-article',
            'attrs' => [
                'onclick' => 'openDeleteArticleModal()'
            ]
        ]); ?>
        
        <!-- Publish Button -->
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

<!-- Delete Confirmation Modal -->
<?php component('widget/modal-delete-confirmation', [
    'modalId' => 'modal-delete-article'
]); ?>

<!-- Main Card Container for Article Editing -->
<div class="mx-32 mb-6">
    <div class="border border-border-soft rounded-2xl p-6 bg-white-neutral h-[800px] flex flex-col">
        <!-- Title Input with existing data -->
        <div class="mb-8">
            <input 
                type="text" 
                id="article-title" 
                placeholder="Judul Artikel..." 
                value="<?= e($article['title'] ?? '') ?>"
                class="w-full font-normal text-[32px] text-black-soft placeholder-white-soft bg-transparent border-none outline-none focus:ring-0"
            />
        </div>
        
        <!-- Content Editor with existing data -->
        <div class="flex-1 overflow-y-auto mb-5">
            <textarea 
                id="article-content" 
                placeholder="Mulai menulis artikel..." 
                class="w-full h-full font-normal text-[16px] text-black-soft placeholder:font-normal placeholder:text-[16px] placeholder-white-soft bg-transparent border-none outline-none focus:ring-0 resize-none"
            ><?= e($article['content'] ?? '') ?></textarea>
        </div>
    </div>
</div>

<!-- Hidden input to store article ID -->
<input type="hidden" id="article-id" value="<?= e($article['id'] ?? '') ?>">

<script>
// Validation helper function
function validateArticleForm() {
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

// Function to open delete article modal
function openDeleteArticleModal() {
    const articleTitle = '<?= addslashes($article['title'] ?? '') ?>';
    const articleId = '<?= e($article['id'] ?? '') ?>';
    
    openModalDeleteArticle(
        'Hapus Artikel',
        'Apakah Anda yakin ingin menghapus artikel "' + articleTitle + '"? Tindakan ini tidak dapat dibatalkan.',
        function() {
            deleteArticle(articleId);
        }
    );
}

// Function to delete article
function deleteArticle(articleId) {
    // Send AJAX request
    fetch('<?= url('/admin/articles') ?>/' + articleId + '/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success', 3000);
            setTimeout(() => window.location.href = '<?= url('/admin/articles') ?>', 1000);
        } else {
            showToast(data.message, 'error', 3000);
        }
    })
    .catch(error => {
        showToast('Terjadi kesalahan saat menghapus artikel', 'error', 3000);
    });
}

// Pre-fill modal data with existing article data
document.addEventListener('DOMContentLoaded', function() {
    const authorName = '<?= e($article['author_name'] ?? '') ?>';
    const featuredImage = '<?= e(asset_url($article['featured_image'] ?? '')) ?>';
    
    if (authorName) {
        const authorInput = document.getElementById('author-name');
        if (authorInput) {
            authorInput.value = authorName;
        }
    }
    
    // Show existing featured image when editing
    if (featuredImage && typeof showImagePreview === 'function') {
        showImagePreview(featuredImage);
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
