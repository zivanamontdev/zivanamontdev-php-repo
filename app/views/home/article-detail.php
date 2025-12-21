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

<!-- Back Button -->
<div class="container mx-auto mt-[52px] mb-[32px]">
    <?php component('button', ['text' => 'Kembali ke Artikel', 'variant' => '4', 'href' => url('/articles')]); ?>
</div>

<!-- Featured Image -->
<section class="container mx-auto mb-[32px]">
    <div class="w-full h-[432px] rounded-[16px] overflow-hidden">
        <img 
            src="<?= getArticleFeaturedImage($article['featured_image']) ?>" 
            alt="<?= e($article['title']) ?>" 
            class="w-full h-full object-cover object-center"
        >
    </div>
</section>

<!-- Article Content Card -->
<section class="container mx-auto mb-[32px]">
    <div class="bg-white-neutral rounded-[24px] p-[32px]">
        <!-- Title -->
        <h1 class="font-bold text-[32px] leading-[100%] text-black-soft mb-[8px]">
            <?= e($article['title']) ?>
        </h1>
        
        <!-- Author & Date Row -->
        <div class="flex justify-between items-center mb-[24px]">
            <span class="font-normal text-[20px] leading-[100%] text-black-highlight">
                <?= e($article['author_name']) ?>
            </span>
            <span class="font-normal text-[20px] leading-[100%] text-black-highlight">
                <?= formatArticleDate($article['published_at']) ?>
            </span>
        </div>
        
        <!-- Description/Content -->
        <div class="font-normal text-[20px] leading-[150%] text-black-highlight article-content">
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
<section class="container mx-auto mt-[32px]">
    <?php component('badge', ['text' => 'Baca Artikel Lainnya']); ?>
    
    <div class="mt-[16px]">
        <?php component('widget/articles/articles_other', ['articles' => $otherArticles]); ?>
    </div>
</section>
<?php endif; ?>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
