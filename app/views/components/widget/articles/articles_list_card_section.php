<?php
/**
 * Articles List Card Section Widget Component
 * 
 * List card layout untuk menampilkan artikel/berita
 * Layout horizontal dengan perbandingan 1:7 (grid 8)
 * Section 1: Gambar (tinggi 224px, radius 24px)
 * Section 2: Card konten (white_neutral, padding 24px, radius 24px)
 * 
 * @param array $articles - Array of articles to display
 */

// Get articles from passed parameter
$listArticles = $__component_data__['articles'] ?? [];

// If no articles provided, return empty
if (empty($listArticles)) {
    return;
}

// Helper function to format date in Indonesian
if (!function_exists('formatIndonesianDateList')) {
    function formatIndonesianDateList($dateString) {
        $timestamp = strtotime($dateString);
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }
}

// Helper to get image URL
if (!function_exists('getArticleImageUrlList')) {
    function getArticleImageUrlList($imagePath) {
        if (empty($imagePath)) {
            return asset('images/default-article.jpg');
        }
        
        // If image is from R2 (contains R2_PUBLIC_URL), return as-is
        if (defined('R2_PUBLIC_URL') && strpos($imagePath, R2_PUBLIC_URL) === 0) {
            return $imagePath;
        }
        
        // If path already includes 'uploads/', use it directly
        if (strpos($imagePath, 'uploads/') === 0) {
            return url($imagePath);
        }
        
        return asset('uploads/' . $imagePath);
    }
}
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
                <div class="h-[224px] rounded-[24px] overflow-hidden bg-gray-100 flex items-center justify-center">
                    <img 
                        src="<?= getArticleImageUrlList($article['featured_image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center scale-125"
                        onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<svg class=&quot;w-12 h-12 text-gray-300&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;1.5&quot; d=&quot;M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z&quot;></path></svg>';"
                    >
                </div>
            </div>
            
            <!-- Section 2: Content Card (7/8 width) -->
            <div class="flex-1">
                <div class="bg-white-neutral rounded-[24px] p-[24px] h-[224px] flex flex-col">
                    <!-- Title -->
                    <h3 class="font-bold text-[20px] leading-[32px] text-black-soft">
                        <?= e($article['title']) ?>
                    </h3>
                    
                    <!-- Author & Date Row -->
                    <div class="flex justify-between items-center mt-[4px]">
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft">
                            <?= e($article['author_name']) ?>
                        </span>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= formatIndonesianDateList($article['published_at']) ?>
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft line-clamp-3 description-clamp max-h-[84px] flex-grow mt-auto mb-auto">
                        <?= e($article['excerpt']) ?>
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
