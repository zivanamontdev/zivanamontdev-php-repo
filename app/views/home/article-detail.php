<?php 
// Get article data from controller
$article = $article ?? null;
$otherArticles = $otherArticles ?? [];

// Redirect to 404 if article not found
if (!$article) {
    http_response_code(404);
    require VIEW_PATH . '/errors/404.php';
    exit;
}

// Helper function to format date in Indonesian
function formatArticleDate($dateString) {
    $timestamp = strtotime($dateString);
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

// Helper to get image URL
function getArticleFeaturedImage($imagePath) {
    if (empty($imagePath)) {
        return url('images/default-article.jpg');
    }
    // If path already includes 'uploads/', use it directly
    if (strpos($imagePath, 'uploads/') === 0) {
        return url($imagePath);
    }
    return url('uploads/' . $imagePath);
}

$pageTitle = $article['title'];
ob_start(); 
?>

<style>
@media (max-width: 640px) {
    .mobile-back-button .btn-component {
        font-size: 14px !important;
    }
    
    .mobile-featured-image {
        height: 320px !important;
    }
    
    .article-header-wrapper {
        display: flex;
        flex-direction: column;
    }
    
    .article-meta-row {
        display: contents !important;
    }
    
    .mobile-article-date {
        order: 1;
        font-size: 16px !important;
        line-height: 28px !important;
        margin-bottom: 8px !important;
    }
    
    .article-title-mobile {
        order: 2;
        font-size: 24px !important;
        line-height: 38px !important;
        margin-bottom: 8px !important;
    }
    
    .mobile-article-author {
        order: 3;
        font-size: 16px !important;
        line-height: 28px !important;
        margin-bottom: 24px !important;
    }
    
    .mobile-article-content {
        font-size: 16px !important;
        line-height: 28px !important;
    }
    
    /* Other Articles Section Mobile */
    .mobile-badge-center {
        display: flex;
        justify-content: center;
    }
    
    .mobile-other-articles-scroll {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 10px;
    }
    
    .mobile-other-articles-scroll::-webkit-scrollbar {
        display: none;
    }
    
    .mobile-other-article-card {
        width: 240px;
        min-width: 240px;
        flex-shrink: 0;
        background: #FCFCFD;
        border-radius: 24px;
        overflow: hidden;
    }
    
    .mobile-other-article-image {
        width: 100%;
        height: 140px;
        overflow: hidden;
    }
    
    .mobile-other-article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .mobile-other-article-content {
        padding: 16px;
    }
    
    .mobile-other-article-date {
        font-size: 14px;
        line-height: 150%;
        color: #A4A4A4;
        margin-bottom: 8px;
    }
    
    .mobile-other-article-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 120%;
        color: #151419;
        margin-bottom: 8px;
    }
    
    .mobile-other-article-author {
        font-size: 14px;
        line-height: 150%;
        color: #A4A4A4;
        margin-bottom: 8px;
    }
    
    .mobile-other-article-excerpt {
        font-size: 14px;
        line-height: 150%;
        color: #151419;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Hide desktop other articles */
    .desktop-other-articles {
        display: none;
    }
}

@media (min-width: 641px) {
    .mobile-other-articles {
        display: none;
    }
}
</style>

<!-- Back Button -->
<div class="container mx-auto px-5 mt-[52px] mb-[32px] mobile-back-button">
    <?php component('button', ['text' => 'Kembali ke Artikel', 'variant' => '4', 'href' => url('/articles')]); ?>
</div>

<!-- Featured Image -->
<section class="container mx-auto px-5 mb-[32px]">
    <div class="w-full h-[432px] mobile-featured-image rounded-[16px] overflow-hidden">
        <img 
            src="<?= getArticleFeaturedImage($article['featured_image']) ?>" 
            alt="<?= e($article['title']) ?>" 
            class="w-full h-full object-cover object-center"
        >
    </div>
</section>

<!-- Article Content Card -->
<section class="container mx-auto px-5 mb-[32px]">
    <div class="bg-white-neutral rounded-[24px] p-[32px]">
        <div class="article-header-wrapper">
            <!-- Title -->
            <h1 class="font-bold text-[32px] leading-[100%] text-black-soft mb-[8px] article-title-mobile">
                <?= e($article['title']) ?>
            </h1>
            
            <!-- Author & Date Row -->
            <div class="flex justify-between items-center mb-[24px] article-meta-row">
                <span class="font-normal text-[20px] leading-[100%] text-black-highlight mobile-article-author">
                    <?= e($article['author_name']) ?>
                </span>
                <span class="font-normal text-[20px] leading-[100%] text-black-highlight mobile-article-date">
                    <?= formatArticleDate($article['published_at']) ?>
                </span>
            </div>
        </div>
        
        <!-- Description/Content -->
        <div class="font-normal text-[20px] leading-[150%] text-black-highlight mobile-article-content article-content">
            <?= nl2br(e($article['content'])) ?>
        </div>
    </div>
</section>

<style>
.article-content br {
    content: "";
    display: block;
    margin-bottom: 0.5em;
}
</style>

<!-- Other Articles Section -->
<?php if (!empty($otherArticles)): ?>
<section class="container mx-auto px-5 mt-[32px]">
    <div class="mobile-badge-center">
        <?php component('badge', ['text' => 'Baca Artikel Lainnya']); ?>
    </div>
    
    <!-- Desktop Version -->
    <div class="mt-[16px] desktop-other-articles">
        <?php component('widget/articles/articles_other', ['articles' => $otherArticles]); ?>
    </div>
    
    <!-- Mobile Version -->
    <div class="mt-[16px] mobile-other-articles">
        <div class="mobile-other-articles-scroll">
            <?php foreach ($otherArticles as $article): ?>
                <div class="mobile-other-article-card">
                    <!-- Image -->
                    <div class="mobile-other-article-image">
                        <img src="<?= getArticleFeaturedImage($article['featured_image']) ?>" alt="<?= e($article['title']) ?>">
                    </div>
                    
                    <!-- Content -->
                    <div class="mobile-other-article-content">
                        <!-- Date -->
                        <div class="mobile-other-article-date">
                            <?= formatArticleDate($article['published_at']) ?>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="mobile-other-article-title">
                            <?= e($article['title']) ?>
                        </h3>
                        
                        <!-- Author -->
                        <div class="mobile-other-article-author">
                            <?= e($article['author_name']) ?>
                        </div>
                        
                        <!-- Excerpt -->
                        <div class="mobile-other-article-excerpt">
                            <?= e(substr(strip_tags($article['content']), 0, 100)) ?>...
                        </div>
                        
                        <!-- Read More Link -->
                        <a href="<?= url('/article/' . $article['slug']) ?>" class="font-normal text-[14px] leading-[28px] text-primary hover:opacity-80 transition-opacity">
                            Baca Artikel
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
