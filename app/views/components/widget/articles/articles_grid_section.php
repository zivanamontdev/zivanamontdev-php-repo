<?php
/**
 * Articles Grid Section Widget Component
 * 
 * Grid layout untuk menampilkan artikel/berita di halaman articles
 * Section 1: Card vertikal (image atas, konten bawah)
 * Section 2 & 3: Card horizontal (image kiri, konten kanan)
 */

// Load dummy data
require VIEW_PATH . '/data/articles_data.php';

// Get first 3 articles for grid
$gridArticles = array_slice($dummyArticles, 0, 3);
?>

<style>
    .description-clamp {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<section class="container mx-auto">
    <div class="flex gap-[32px]">
        <!-- Section 1: Main Article Card -->
        <div class="flex-1">
            <div class="bg-white-neutral rounded-[24px] h-[556px] overflow-hidden">
                <!-- Image -->
                <div class="h-[240px] w-full overflow-hidden rounded-t-[24px]">
                    <img 
                        src="<?= url('images/' . $gridArticles[0]['image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center"
                    >
                </div>
                
                <!-- Content with padding -->
                <div class="p-[24px] flex flex-col h-[316px]">
                    <!-- Title & Date Row -->
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-[20px] leading-[32px] text-black-soft truncate">
                            <?= $gridArticles[0]['title'] ?>
                        </h3>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= $gridArticles[0]['date'] ?>
                        </span>
                    </div>
                    
                    <!-- Author -->
                    <span class="font-normal text-[16px] leading-[28px] text-white-soft mt-[4px] mb-[16px]">
                        <?= $gridArticles[0]['author'] ?>
                    </span>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft h-[112px] overflow-hidden mb-[16px] line-clamp-4 flex-grow">
                        <?= $gridArticles[0]['description'] ?>
                    </p>
                    
                    <!-- Read More Link -->
                    <a href="<?= url('/article-detail?id=' . $gridArticles[0]['id']) ?>" class="font-normal text-[16px] leading-[28px] text-primary hover:opacity-80 transition-opacity mt-auto">
                        Baca Artikel
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Section 2 & 3: Two Cards Stacked Vertically -->
        <div class="flex-1 flex flex-col gap-[24px]">
            <?php for ($i = 1; $i <= 2; $i++): ?>
            <!-- Section <?= $i + 1 ?>: <?= $gridArticles[$i]['title'] ?> -->
            <div class="flex-1 bg-white-neutral rounded-[24px] flex overflow-hidden">
                <!-- Image -->
                <div class="w-[240px] flex-shrink-0 overflow-hidden rounded-l-[24px] mr-[40px]">
                    <img 
                        src="<?= url('images/' . $gridArticles[$i]['image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center"
                    >
                </div>
                
                <!-- Content with padding -->
                <div class="p-[24px] pl-0 flex flex-col flex-1">
                    <!-- Title -->
                    <h3 class="font-bold text-[20px] leading-[32px] text-black-soft">
                        <?= $gridArticles[$i]['title'] ?>
                    </h3>
                    
                    <!-- Author & Date Row -->
                    <div class="flex justify-between items-center mt-[4px] mb-[8px]">
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft">
                            <?= $gridArticles[$i]['author'] ?>
                        </span>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= $gridArticles[$i]['date'] ?>
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft mb-[8px] line-clamp-3 description-clamp max-h-[84px]">
                        <?= $gridArticles[$i]['description'] ?>
                    </p>
                    
                    <!-- Read More Link -->
                    <a href="<?= url('/article-detail?id=' . $gridArticles[$i]['id']) ?>" class="font-normal text-[16px] leading-[28px] text-primary hover:opacity-80 transition-opacity mt-auto">
                        Baca Artikel
                    </a>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
