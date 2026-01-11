<?php
/**
 * Articles Grid Section Widget Component
 * 
 * Grid layout untuk menampilkan artikel/berita di halaman articles
 * Section 1: Card vertikal (image atas, konten bawah)
 * Section 2 & 3: Card horizontal (image kiri, konten kanan)
 * 
 * @param array $articles - Array of articles to display (expects at least 3 articles)
 */

// Get articles from passed parameter
$gridArticles = $__component_data__['articles'] ?? [];

// If no articles provided, return empty
if (empty($gridArticles)) {
    return;
}

// Take only first 3 articles
$gridArticles = array_slice($gridArticles, 0, 3);

// Helper function to format date in Indonesian
if (!function_exists('formatIndonesianDate')) {
    function formatIndonesianDate($dateString) {
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
if (!function_exists('getArticleImageUrl')) {
    function getArticleImageUrl($imagePath) {
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

<section class="container mx-auto">
    <div class="flex gap-[32px]">
        <!-- Section 1: Main Article Card -->
        <div class="flex-1">
            <div class="bg-white-neutral rounded-[24px] h-[556px] overflow-hidden">
                <!-- Image -->
                <div class="h-[240px] w-full overflow-hidden rounded-t-[24px] bg-gray-100 flex items-center justify-center">
                    <img 
                        src="<?= getArticleImageUrl($gridArticles[0]['featured_image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center"
                        onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<svg class=&quot;w-16 h-16 text-gray-300&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;1.5&quot; d=&quot;M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z&quot;></path></svg>';"
                    >
                </div>
                
                <!-- Content with padding -->
                <div class="p-[24px] flex flex-col h-[316px]">
                    <!-- Title & Date Row -->
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-[20px] leading-[32px] text-black-soft truncate">
                            <?= e($gridArticles[0]['title']) ?>
                        </h3>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= formatIndonesianDate($gridArticles[0]['published_at']) ?>
                        </span>
                    </div>
                    
                    <!-- Author -->
                    <span class="font-normal text-[16px] leading-[28px] text-white-soft mt-[4px] mb-[16px]">
                        <?= e($gridArticles[0]['author_name']) ?>
                    </span>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft h-[112px] overflow-hidden mb-[16px] line-clamp-4 flex-grow">
                        <?= e($gridArticles[0]['excerpt']) ?>
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
            <?php if (isset($gridArticles[$i])): ?>
            <!-- Section <?= $i + 1 ?>: <?= e($gridArticles[$i]['title']) ?> -->
            <div class="h-[266px] bg-white-neutral rounded-[24px] flex overflow-hidden">
                <!-- Image -->
                <div class="w-[240px] h-full flex-shrink-0 overflow-hidden rounded-l-[24px] mr-[40px] bg-gray-100 flex items-center justify-center">
                    <img 
                        src="<?= getArticleImageUrl($gridArticles[$i]['featured_image']) ?>" 
                        alt="Artikel" 
                        class="w-full h-full object-cover object-center"
                        onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<svg class=&quot;w-16 h-16 text-gray-300&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;1.5&quot; d=&quot;M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z&quot;></path></svg>';"
                    >
                </div>
                
                <!-- Content with padding -->
                <div class="p-[24px] pl-0 flex flex-col flex-1">
                    <!-- Title -->
                    <h3 class="font-bold text-[20px] leading-[32px] text-black-soft">
                        <?= e($gridArticles[$i]['title']) ?>
                    </h3>
                    
                    <!-- Author & Date Row -->
                    <div class="flex justify-between items-center mt-[4px] mb-[8px]">
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft">
                            <?= e($gridArticles[$i]['author_name']) ?>
                        </span>
                        <span class="font-normal text-[16px] leading-[28px] text-white-soft text-right flex-shrink-0">
                            <?= formatIndonesianDate($gridArticles[$i]['published_at']) ?>
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="font-normal text-[16px] leading-[28px] text-black-soft mb-[8px] line-clamp-3 description-clamp max-h-[84px]">
                        <?= e($gridArticles[$i]['excerpt']) ?>
                    </p>
                    
                    <!-- Read More Link -->
                    <a href="<?= url('/article-detail?id=' . $gridArticles[$i]['id']) ?>" class="font-normal text-[16px] leading-[28px] text-primary hover:opacity-80 transition-opacity mt-auto">
                        Baca Artikel
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
