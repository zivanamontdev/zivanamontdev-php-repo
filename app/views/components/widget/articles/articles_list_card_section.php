<?php
/**
 * Articles List Card Section Widget Component
 * 
 * List card layout untuk menampilkan artikel/berita
 * Layout horizontal dengan perbandingan 1:7 (grid 8)
 * Section 1: Gambar (tinggi 224px, radius 24px)
 * Section 2: Card konten (white_neutral, padding 24px, radius 24px)
 */

// Load dummy data
require VIEW_PATH . '/data/articles_data.php';

// Get articles starting from index 3 (skip first 3 which are in grid)
$listArticles = array_slice($dummyArticles, 3);
?>

<style>
    .description-clamp {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<section class="container mx-auto mt-[52px]">
    <div class="flex flex-col gap-[24px]">
        <?php foreach ($listArticles as $article): ?>
        <div class="flex gap-[24px]">
            <!-- Section 1: Image (1/8 width) -->
            <div class="w-1/8 flex-shrink-0" style="width: 12.5%;">
                <div class="h-[224px] rounded-[24px] overflow-hidden">
                    <img 
                        src="<?= url('images/' . $article['image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center scale-125"
                    >
                </div>
            </div>
            
            <!-- Section 2: Content Card (7/8 width) -->
            <div class="flex-1">
                <div class="bg-white-neutral rounded-[24px] p-[24px] h-[224px] flex flex-col">
                    <!-- Title -->
                    <h3 class="font-bold text-[20px] leading-[32px] text-black-soft">
                        <?= $article['title'] ?>
                    </h3>
                    
                    <!-- Author & Date Row -->
                    <div class="flex justify-between items-center mt-[4px]">
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft">
                            <?= $article['author'] ?>
                        </span>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= $article['date'] ?>
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft line-clamp-3 description-clamp max-h-[84px] flex-grow mt-auto mb-auto">
                        <?= $article['description'] ?>
                    </p>
                    
                    <!-- Read More Link -->
                    <a href="<?= url('/article-detail?id=' . $article['id']) ?>" class="font-normal text-[16px] leading-[28px] text-primary hover:opacity-80 transition-opacity mt-auto">
                        Baca Artikel
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
