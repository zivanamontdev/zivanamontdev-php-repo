<?php
/**
 * Articles Other Widget Component
 * 
 * Menampilkan artikel lainnya dalam format horizontal card
 * Lebar fixed 450px per card
 * Layout: Image kiri, konten kanan (judul, deskripsi, tombol)
 * 
 * Props:
 * - articles: array of articles to display (from parent component)
 * - currentId: ID of current article being viewed (to exclude from list)
 */

// If no articles passed, load from dummy data
if (empty($articles)) {
    require_once VIEW_PATH . '/data/articles_data.php';
    $currentId = $currentId ?? 1;
    $articles = getOtherArticles($currentId, 2);
}
?>

<style>
    .title-clamp {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .description-clamp-other {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="flex gap-[24px]">
    <?php foreach ($articles as $article): ?>
    <div class="w-[450px] flex-shrink-0 bg-white-neutral rounded-[24px] flex overflow-hidden">
        <!-- Image -->
        <div class="w-[160px] flex-shrink-0 overflow-hidden rounded-l-[24px]">
            <img 
                src="<?= url('images/' . $article['image']) ?>" 
                alt="<?= e($article['title']) ?>" 
                class="w-full h-full object-cover object-center"
            >
        </div>
        
        <!-- Content with padding -->
        <div class="p-[16px] flex flex-col flex-1">
            <!-- Title -->
            <h3 class="font-bold text-[20px] leading-[32px] text-black-soft mb-[16px] line-clamp-2 title-clamp">
                <?= e($article['title']) ?>
            </h3>
            
            <!-- Description -->
            <p class="font-normal text-[16px] leading-[28px] text-black-soft mb-[16px] line-clamp-2 description-clamp-other max-h-[72px]">
                <?= e($article['excerpt']) ?>
            </p>
            
            <!-- Read More Link -->
            <a href="<?= url('/article-detail?id=' . $article['id']) ?>" class="font-normal text-[16px] leading-[28px] text-primary hover:opacity-80 transition-opacity mt-auto">
                Baca Artikel
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
