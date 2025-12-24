<?php 
$pageTitle = 'Articles';

// Get articles data from controller
$articles = $articles ?? [];

// Split articles: first 3 for grid, rest for list
$gridArticles = array_slice($articles, 0, 3);
$listArticles = array_slice($articles, 3);

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
        return url('images/default-article.jpg');
    }
    if (strpos($imagePath, 'uploads/') === 0) {
        return url($imagePath);
    }
    return url('uploads/' . $imagePath);
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
        z-index: 100;
    }
    
    .mobile-article-card {
        background: #FCFCFD;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 16px;
        position: relative;
        z-index: 100;
    }
    
    .mobile-article-card-image {
        width: 100%;
        height: 180px;
        overflow: hidden;
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
        z-index: 100;
    }
}

@media (min-width: 641px) {
    .mobile-only {
        display: none;
    }
}
</style>

<!-- Page Header -->
<div class="mb-[80px]">
    <?php component('page_hero', ['title' => 'Artikel dan Berita Terkini', 'variant' => 'primary']); ?>
</div>

<?php if (empty($articles)): ?>
    <!-- No Articles Message -->
    <section class="container mx-auto px-5">
        <div class="bg-white-neutral rounded-[24px] p-[40px] text-center">
            <p class="font-normal text-[20px] leading-[150%] text-black-highlight">
                Belum ada artikel yang dipublikasikan. Silakan tambah artikel melalui halaman admin.
            </p>
        </div>
    </section>
<?php else: ?>
    <!-- Desktop Version -->
    <div class="desktop-only">
        <!-- Section Grid Berita -->
        <?php if (!empty($gridArticles)): ?>
            <?php component('widget/articles/articles_grid_section', ['articles' => $gridArticles]); ?>
        <?php endif; ?>

        <!-- Section List Card Berita -->
        <?php if (!empty($listArticles)): ?>
            <?php component('widget/articles/articles_list_card_section', ['articles' => $listArticles]); ?>
        <?php endif; ?>

        <!-- Load More Button -->
        <?php if (!empty($pagination) && isset($pagination['last_page']) && $pagination['last_page'] > 1): ?>
        <div class="container mx-auto px-5 mt-[32px] flex justify-end">
            <?php 
            $nextPage = ($pagination['current_page'] ?? 1) + 1;
            $href = $nextPage <= $pagination['last_page'] ? url('/articles?page=' . $nextPage) : '#';
            component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '5', 'href' => $href]); 
            ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Mobile Version -->
    <div class="mobile-only">
        <div class="mobile-articles-container">
            <?php foreach ($articles as $index => $article): ?>
                <div class="mobile-article-item <?= ($index < 3) ? 'show' : '' ?>">
                    <div class="mobile-article-card">
                        <!-- Image -->
                        <div class="mobile-article-card-image">
                            <img src="<?= getArticleImage($article['featured_image']) ?>" alt="<?= e($article['title']) ?>">
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
    </div>
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (!loadMoreBtn) return;
    
    let showingAll = false;
    
    loadMoreBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const articleItems = document.querySelectorAll('.mobile-article-item');
        
        if (!showingAll) {
            // Show all articles
            articleItems.forEach(function(item) {
                item.classList.add('show');
            });
            loadMoreBtn.innerText = 'Tampilkan Lebih Sedikit';
            showingAll = true;
        } else {
            // Show only first 3
            articleItems.forEach(function(item, index) {
                if (index >= 3) {
                    item.classList.remove('show');
                }
            });
            loadMoreBtn.innerText = 'Tampilkan Lebih Banyak';
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
});
</script>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
