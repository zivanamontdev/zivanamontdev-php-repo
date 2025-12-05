<?php 
// Load dummy data
require VIEW_PATH . '/data/articles_data.php';

// Get article by id from query parameter
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$currentArticle = getArticleById($articleId, $dummyArticles);

// Fallback to first article if not found
if (!$currentArticle) {
    $currentArticle = $dummyArticles[0];
}

// Get other articles for "Baca Artikel Lainnya" section
$otherArticles = getOtherArticles($currentArticle['id'], 2, $dummyArticles);

$pageTitle = $currentArticle['title'];
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
            src="<?= url('images/' . $currentArticle['image']) ?>" 
            alt="<?= e($currentArticle['title']) ?>" 
            class="w-full h-full object-cover object-center"
        >
    </div>
</section>

<!-- Article Content Card -->
<section class="container mx-auto mb-[32px]">
    <div class="bg-white-neutral rounded-[24px] p-[32px]">
        <!-- Title -->
        <h1 class="font-bold text-[32px] leading-[100%] text-black-soft mb-[8px]">
            <?= e($currentArticle['title']) ?>
        </h1>
        
        <!-- Author & Date Row -->
        <div class="flex justify-between items-center mb-[24px]">
            <span class="font-normal text-[20px] leading-[100%] text-black-highlight">
                <?= e($currentArticle['author']) ?>
            </span>
            <span class="font-normal text-[20px] leading-[100%] text-black-highlight">
                <?php 
                    $date = $currentArticle['date_full'];
                    $timestamp = strtotime($date);
                    $bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    echo date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
                ?>
            </span>
        </div>
        
        <!-- Description/Content -->
        <div class="font-normal text-[20px] leading-[150%] text-black-highlight">
            <?= $currentArticle['content'] ?>
        </div>
    </div>
</section>

<!-- Other Articles Section -->
<section class="container mx-auto mt-[32px]">
    <?php component('badge', ['text' => 'Baca Artikel Lainnya']); ?>
    
    <div class="mt-[16px]">
        <?php component('widget/articles/articles_other', ['articles' => $otherArticles, 'currentId' => $currentArticle['id']]); ?>
    </div>
</section>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
