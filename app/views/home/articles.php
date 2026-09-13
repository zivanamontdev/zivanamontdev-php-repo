<?php
$pageTitle = 'Articles';

// Get articles data from controller
$articles = $articles ?? [];

// Debug: Log article count
error_log("Articles page - Total articles loaded: " . count($articles));
error_log("Articles page - Articles IDs: " . implode(', ', array_column($articles, 'id')));

// Split articles: first 3 for grid, next 4 for list, rest hidden initially
$gridArticles = array_slice($articles, 0, 3);
$listArticles = array_slice($articles, 3, 4);
$hiddenArticles = array_slice($articles, 7);

error_log("Articles page - Grid: " . count($gridArticles) . ", List: " . count($listArticles) . ", Hidden: " . count($hiddenArticles));

// Helper function to format date in Indonesian
function formatDate($dateString) {
    $timestamp = strtotime($dateString);
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

// Helper to get image URL
function getArticleImage($imagePath) {
    if (empty($imagePath)) {
        return asset('images/default-article.jpg');
    }
    
    // If image is from R2 (contains R2_PUBLIC_URL), return as-is
    if (defined('R2_PUBLIC_URL') && strpos($imagePath, R2_PUBLIC_URL) === 0) {
        return $imagePath;
    }
    
    if (strpos($imagePath, 'uploads/') === 0) {
        return url($imagePath);
    }
    
    return asset('uploads/' . $imagePath);
}

ob_start(); 
?>

<style>
@media (max-width: 767px) {
    .desktop-only {
        display: none;
    }
    
    .mobile-articles-container {
        padding-left: 20px;
        padding-right: 20px;
        position: relative;
    }
    
    .mobile-article-card {
        background: #FCFCFD;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
    }

    .mobile-article-item:not([hidden]) ~ .mobile-article-item:not([hidden]) {
        margin-top: 16px;
    }
    
    .mobile-article-card-image {
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .mobile-article-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .mobile-article-card-content {
        padding: 16px;
    }
    
    .mobile-article-date {
        font-size: 14px;
        line-height: 150%;
        color: #A4A4A4;
        margin-bottom: 8px;
    }
    
    .mobile-article-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 120%;
        color: #151419;
        margin-bottom: 8px;
    }
    
    .mobile-article-author {
        font-size: 14px;
        line-height: 150%;
        color: #A4A4A4;
        margin-bottom: 8px;
    }
    
    .mobile-article-excerpt {
        font-size: 14px;
        line-height: 150%;
        color: #151419;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .mobile-load-more {
        display: flex;
        justify-content: center;
        margin-top: 32px;
        margin-bottom: 40px;
        position: relative;
    }
}

@media (min-width: 768px) {
    .mobile-only {
        display: none;
    }
}
</style>

<!-- Page Header -->
<?php component('page_hero', ['title' => 'Artikel dan Berita Terkini', 'variant' => 'primary']); ?>

<!-- Debug Info -->
<!-- Total Articles: <?= count($articles) ?> -->
<!-- Grid Articles: <?= count($gridArticles) ?> -->
<!-- List Articles: <?= count($listArticles) ?> -->
<!-- Hidden Articles: <?= count($hiddenArticles) ?> -->

<?php if (empty($articles)): ?>
    <!-- Empty State Placeholder -->
    <section class="container mx-auto px-5">
        <!-- Desktop Placeholder -->
        <div class="hidden md:block">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px] mt-[40px]">
                <div class="bg-white-neutral rounded-[24px] overflow-hidden opacity-60">
                    <!-- Image Placeholder -->
                    <div class="w-full h-[240px] bg-gray-placeholder flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-[24px]">
                        <div class="text-gray-400 text-[14px] mb-[12px]">-- --- ----</div>
                        <h3 class="font-bold text-[20px] md:text-[24px] leading-[32px] text-gray-400 mb-[8px]">
                            Belum Ada Artikel
                        </h3>
                        <div class="text-gray-400 text-[14px] mb-[12px]">Admin</div>
                        <p class="font-normal text-[16px] leading-[28px] text-gray-400">
                            Artikel dan berita terkini akan ditampilkan di sini setelah dipublikasikan melalui halaman admin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Placeholder -->
        <div class="md:hidden mobile-articles-container">
            <div class="mobile-article-card opacity-60">
                <!-- Image Placeholder -->
                <div class="mobile-article-card-image bg-gray-placeholder">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="mobile-article-card-content">
                    <div class="mobile-article-date">-- --- ----</div>
                    <h3 class="mobile-article-title text-gray-400">
                        Belum Ada Artikel
                    </h3>
                    <div class="mobile-article-author">Admin</div>
                    <div class="mobile-article-excerpt text-gray-400">
                        Artikel dan berita terkini akan ditampilkan di sini setelah dipublikasikan melalui halaman admin.
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Section Grid Berita - Desktop Only -->
    <?php if (!empty($gridArticles)): ?>
        <div class="desktop-only">
            <?php component('widget/articles/articles_grid_section', ['articles' => $gridArticles]); ?>
        </div>
    <?php endif; ?>

    <!-- Section List Card Berita - Desktop Only -->
    <?php if (!empty($listArticles)): ?>
        <div class="desktop-only">
            <?php component('widget/articles/articles_list_card_section', ['articles' => $listArticles]); ?>
        </div>
    <?php endif; ?>

    <!-- Hidden Articles Section - Desktop Only -->
    <?php if (!empty($hiddenArticles)): ?>
    <!-- The first seven articles above stay visible; reveal the extra list one row at a time. -->
    <div class="desktop-only" data-reveal data-initial="0">
        <div class="desktop-only pt-[24px]" id="desktop-hidden-articles" data-reveal-content hidden>
            <?php component('widget/articles/articles_list_card_section', [
                'articles' => $hiddenArticles,
                'removeTopMargin' => true,
                'revealItems' => true
            ]); ?>
        </div>
    <!-- Load More Button - Desktop Only -->
    <div class="desktop-only hidden md:flex container mx-auto px-5 mt-[32px] justify-end">
            <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '5', 'id' => 'desktopLoadMoreBtn', 'type' => 'button', 'attrs' => ['data-reveal-more' => '']]); ?>
    </div>
    </div>
    <?php endif; ?>
    
    <!-- Mobile Version -->
    <div class="mobile-articles-container md:hidden" data-reveal data-initial="3">
            <?php foreach ($articles as $index => $article): ?>
                <div class="mobile-article-item" data-reveal-item <?= $index >= 3 ? 'hidden' : '' ?> data-index="<?= $index ?>">
                    <div class="mobile-article-card">
                        <!-- Image -->
                        <div class="mobile-article-card-image">
                            <img 
                                src="<?= getArticleImage($article['featured_image']) ?>" 
                                alt="<?= e($article['title']) ?>"
                                onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<svg class=&quot;w-12 h-12 text-gray-300&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;1.5&quot; d=&quot;M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z&quot;></path></svg>';"
                            >
                        </div>
                        
                        <!-- Content -->
                        <div class="mobile-article-card-content">
                            <!-- Date (di atas title) -->
                            <div class="mobile-article-date">
                                <?= formatDate($article['published_at']) ?>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="mobile-article-title">
                                <?= e($article['title']) ?>
                            </h3>
                            
                            <!-- Author -->
                            <div class="mobile-article-author">
                                <?= e($article['author_name']) ?>
                            </div>
                            
                            <!-- Excerpt -->
                            <div class="mobile-article-excerpt">
                                <?= e(substr(strip_tags($article['content']), 0, 150)) ?>...
                            </div>
                            
                            <!-- Read More Link -->
                            <a href="<?= url('/article/' . $article['slug']) ?>" class="font-normal text-[14px] leading-[28px] text-primary hover:opacity-80 transition-opacity">
                                Baca Artikel
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Load More Button -->
            <?php if (count($articles) > 3): ?>
            <div class="mobile-load-more">
                <button 
                    type="button" 
                    id="loadMoreBtn"
                    data-reveal-more
                    class="btn-component inline-flex items-center justify-center font-bold transition-all duration-200 py-2 px-6 rounded-xl bg-white-neutral text-black-soft font-normal text-base leading-[28px] opacity-50 hover:opacity-90 active:scale-95 cursor-pointer"
                >
                    Tampilkan Lebih Banyak
                </button>
            </div>
            <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (($pagination['last_page'] ?? 1) > 1): ?>
<nav aria-label="Halaman artikel" class="container mx-auto px-5 mt-8 flex justify-center gap-4">
    <?php if (($pagination['current_page'] ?? 1) > 1): ?>
        <?php component('button', ['text' => 'Artikel Sebelumnya', 'variant' => '3', 'href' => url('/articles?page=' . ($pagination['current_page'] - 1))]); ?>
    <?php endif; ?>
    <?php if (($pagination['current_page'] ?? 1) < $pagination['last_page']): ?>
        <?php component('button', ['text' => 'Artikel Berikutnya', 'variant' => '3', 'href' => url('/articles?page=' . ($pagination['current_page'] + 1))]); ?>
    <?php endif; ?>
</nav>
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>



<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
