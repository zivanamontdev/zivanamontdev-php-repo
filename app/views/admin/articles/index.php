<?php
/**
 * Articles/News Management Page
 * Page for managing school articles and news
 */

$pageTitle = 'Artikel/Berita';
$currentPage = $currentPage ?? 'articles';

// Start output buffering
ob_start();

// Get articles data from controller
$articles = $articles ?? [];
$pagination = $pagination ?? null;
?>

<style>
.article-status-tooltip-bubble {
    display: none;
}

.article-status-tooltip:hover .article-status-tooltip-bubble,
.article-status-tooltip:focus-within .article-status-tooltip-bubble {
    display: block;
}
</style>

<!-- Admin Navbar -->
<?php component('admin-navbar', ['title' => 'Artikel']); ?>

<!-- Articles Card -->
<div class="mt-4">
    <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
        <div class="flex items-center justify-between">
            <!-- Title and Description -->
            <div class="flex flex-col items-start">
                <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Daftar Artikel</h3>
                <div class="flex items-center gap-2">
                    <p class="font-normal text-[14px] leading-[21px] text-white-soft">Artikel yang telah dibuat akan muncul di sini</p>
                    <div class="article-status-tooltip relative flex items-center">
                        <button
                            type="button"
                            aria-label='Hanya artikel dengan status "Published" yang akan tampil di halaman publik'
                            class="w-[18px] h-[18px] rounded-full border border-primary/30 bg-primary/10 text-primary text-[11px] leading-none font-bold flex items-center justify-center cursor-help focus:outline-none focus:ring-2 focus:ring-primary/20"
                        >
                            i
                        </button>
                        <div class="article-status-tooltip-bubble pointer-events-none absolute left-1/2 top-[28px] z-20 w-[260px] -translate-x-1/2 rounded-xl border border-border-light bg-white-neutral px-4 py-3 text-[12px] font-normal leading-[18px] text-black-soft shadow-lg">
                            <span class="absolute left-1/2 top-[-6px] h-3 w-3 -translate-x-1/2 rotate-45 border-l border-t border-border-light bg-white-neutral"></span>
                            Hanya artikel dengan status "Published" yang akan tampil di halaman publik
                        </div>
                    </div>
                </div>
                <p style="display: none;">
                    💡 Hanya artikel dengan status "Published" yang akan tampil di halaman publik
                </p>
            </div>
            
            <!-- Add Article Button -->
            <div class="flex-shrink-0">
                <?php component('button', [
                    'variant' => '8',
                    'icon' => 'plus',
                    'text' => 'Tambah Artikel/Berita',
                    'type' => 'button',
                    'id' => 'btn-add-article',
                    'attrs' => [
                        'onclick' => 'window.location.href="' . url('/admin/articles/create') . '"'
                    ]
                ]); ?>
            </div>
        </div>
        
        <!-- Articles List -->
        <div class="mt-5">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <?php component('widget/articles/articles_list_data', [
                        'id' => $article['id'],
                        'title' => $article['title'],
                        'excerpt' => $article['excerpt'] ?? substr(strip_tags($article['content']), 0, 150),
                        'author' => $article['author_name'],
                        'date' => $article['created_at'],
                        'image' => $article['featured_image'] ?? '',
                        'status' => $article['status'] ?? 'published'
                    ]); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8 text-white-shadow">
                    <p class="font-normal text-[14px]">Belum ada artikel. Klik "Tambah Artikel/Berita" untuk membuat artikel baru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
