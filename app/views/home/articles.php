<?php 
$pageTitle = 'Articles';

// Get articles data from controller
$articles = $articles ?? [];

// Debug: Log article count and details
error_log("DEBUG ARTICLES - Total articles from controller: " . count($articles));
if (!empty($articles)) {
    foreach ($articles as $idx => $art) {
        error_log("DEBUG ARTICLES - Index $idx: ID={$art['id']}, Title={$art['title']}, Status={$art['status']}, Published={$art['published_at']}");
    }
}

// Split articles: first 3 for grid, next 4 for list, rest hidden initially
$gridArticles = array_slice($articles, 0, 3);
$listArticles = array_slice($articles, 3, 4);
$hiddenArticles = array_slice($articles, 7);

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
@media (max-width: 640px) {
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
        margin-bottom: 16px;
        position: relative;
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
    
    .mobile-article-item {
        display: none;
    }
    
    .mobile-article-item.show {
        display: block;
    }
    
    .mobile-load-more {
        display: flex;
        justify-content: center;
        margin-top: 24px;
        margin-bottom: 40px;
        position: relative;
    }
}

@media (min-width: 641px) {
    .mobile-only {
        display: none;
    }
}
</style>

<!-- Page Header -->
<?php component('page_hero', ['title' => 'Artikel dan Berita Terkini', 'variant' => 'primary']); ?>

<?php if (empty($articles)): ?>
    <!-- No Articles Message --><section class="container mx-auto px-5">
        <div class="bg-white-neutral rounded-[24px] p-[40px] text-center">
            <p class="font-normal text-[20px] leading-[150%] text-black-highlight">
                Belum ada artikel yang dipublikasikan. Silakan tambah artikel melalui halaman admin.
            </p>
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
        <div class="desktop-only hidden" id="desktop-hidden-articles">
            <?php component('widget/articles/articles_list_card_section', ['articles' => $hiddenArticles]); ?>
        </div>
    <?php endif; ?>

    <!-- Load More Button - Desktop Only -->
    <?php if (!empty($hiddenArticles) || (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1 && ($pagination['current_page'] ?? 1) < $pagination['last_page'])): ?>
    <div class="desktop-only hidden md:flex container mx-auto px-5 mt-[32px] justify-end">
        <?php if (!empty($hiddenArticles)): ?>
            <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '5', 'id' => 'desktopLoadMoreBtn', 'type' => 'button']); ?>
        <?php elseif (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1 && ($pagination['current_page'] ?? 1) < $pagination['last_page']): ?>
            <?php 
            $nextPage = ($pagination['current_page'] ?? 1) + 1;
            $href = url('/articles?page=' . $nextPage);
            component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '5', 'href' => $href]); 
            ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Mobile Version -->
    <div class="mobile-articles-container md:hidden">
            <?php foreach ($articles as $index => $article): ?>
                <div class="mobile-article-item <?= ($index < 3) ? 'show' : '' ?>">
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
                <?php component('button', [
                    'text' => 'Tampilkan Lebih Banyak', 
                    'variant' => '5',
                    'id' => 'loadMoreBtn',
                    'type' => 'button'
                ]); ?>
            </div>
            <?php endif; ?>
    </div>
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Load More
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        let showingAll = false;
        
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const articleItems = document.querySelectorAll('.mobile-article-item');
            
            if (!showingAll) {
                // Show all articles
                articleItems.forEach(function(item) {
                    item.classList.add('show');
                });
                loadMoreBtn.textContent = 'Tampilkan Lebih Sedikit';
                showingAll = true;
            } else {
                // Show only first 3
                articleItems.forEach(function(item, index) {
                    if (index >= 3) {
                        item.classList.remove('show');
                    }
                });
                loadMoreBtn.textContent = 'Tampilkan Lebih Banyak';
                showingAll = false;
                
                // Scroll to top of articles
                const container = document.querySelector('.mobile-articles-container');
                if (container) {
                    container.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }
        });
    }
    
    // Desktop Load More
    const desktopLoadMoreBtn = document.getElementById('desktopLoadMoreBtn');
    if (desktopLoadMoreBtn) {
        const hiddenSection = document.getElementById('desktop-hidden-articles');
        let isShowing = false;
        
        desktopLoadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!isShowing) {
                // Show hidden articles
                if (hiddenSection) {
                    hiddenSection.classList.remove('hidden');
                }
                desktopLoadMoreBtn.textContent = 'Tampilkan Lebih Sedikit';
                isShowing = true;
            } else {
                // Hide articles again
                if (hiddenSection) {
                    hiddenSection.classList.add('hidden');
                }
                desktopLoadMoreBtn.textContent = 'Tampilkan Lebih Banyak';
                isShowing = false;
                
                // Scroll to list section
                const listSection = document.querySelector('.desktop-only');
                if (listSection) {
                    listSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
