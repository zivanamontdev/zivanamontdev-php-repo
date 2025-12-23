<?php 
$pageTitle = 'Articles';

// Get articles data from controller
$articles = $articles ?? [];

// Split articles: first 3 for grid, rest for list
$gridArticles = array_slice($articles, 0, 3);
$listArticles = array_slice($articles, 3);

ob_start(); 
?>

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
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
