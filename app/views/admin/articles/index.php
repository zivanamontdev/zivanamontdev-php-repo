<?php
/**
 * Articles/News Management Page
 * Page for managing school articles and news
 */

$pageTitle = 'Artikel/Berita';
$currentPage = $currentPage ?? 'articles';

// Start output buffering
ob_start();

// Get articles data from controller
$articles = $articles ?? [];
$pagination = $pagination ?? null;

// Dummy data for testing
if (empty($articles)) {
    $articles = [
        [
            'id' => 1,
            'title' => 'Kegiatan Pembelajaran Outdoor di Taman Kota',
            'excerpt' => 'Siswa-siswi TK Zivana Mont mengikuti kegiatan pembelajaran outdoor yang menyenangkan di Taman Kota. Mereka belajar mengenal berbagai jenis tanaman dan hewan.',
            'content' => 'Siswa-siswi TK Zivana Mont mengikuti kegiatan pembelajaran outdoor yang menyenangkan di Taman Kota. Mereka belajar mengenal berbagai jenis tanaman dan hewan.',
            'author_name' => 'Bu Siti Aminah',
            'created_at' => '2025-11-27 10:30:00',
            'featured_image' => '/images/activity_logo.png'
        ],
        [
            'id' => 2,
            'title' => 'Peringatan Hari Kartini di TK Zivana Mont',
            'excerpt' => 'Para siswa dan guru merayakan Hari Kartini dengan mengenakan pakaian adat dari berbagai daerah di Indonesia. Acara ini bertujuan untuk mengenalkan keberagaman budaya.',
            'content' => 'Para siswa dan guru merayakan Hari Kartini dengan mengenakan pakaian adat dari berbagai daerah di Indonesia. Acara ini bertujuan untuk mengenalkan keberagaman budaya.',
            'author_name' => 'Bu Rina Kusuma',
            'created_at' => '2025-11-25 14:15:00',
            'featured_image' => ''
        ],
        [
            'id' => 3,
            'title' => 'Workshop Parenting: Mendidik Anak di Era Digital',
            'excerpt' => 'TK Zivana Mont mengadakan workshop parenting dengan tema "Mendidik Anak di Era Digital". Acara ini dihadiri oleh puluhan orang tua siswa.',
            'content' => 'TK Zivana Mont mengadakan workshop parenting dengan tema "Mendidik Anak di Era Digital". Acara ini dihadiri oleh puluhan orang tua siswa.',
            'author_name' => 'Pak Ahmad Wijaya',
            'created_at' => '2025-11-20 09:00:00',
            'featured_image' => '/images/activity_logo.png'
        ],
        [
            'id' => 4,
            'title' => 'Pentas Seni Akhir Semester Ganjil 2025',
            'excerpt' => 'Siswa-siswi menampilkan berbagai pertunjukan seni seperti menyanyi, menari, dan drama. Para orang tua sangat antusias menyaksikan penampilan anak-anak mereka.',
            'content' => 'Siswa-siswi menampilkan berbagai pertunjukan seni seperti menyanyi, menari, dan drama. Para orang tua sangat antusias menyaksikan penampilan anak-anak mereka.',
            'author_name' => 'Bu Dewi Sartika',
            'created_at' => '2025-11-18 13:30:00',
            'featured_image' => ''
        ],
        [
            'id' => 5,
            'title' => 'Kunjungan Edukatif ke Museum Nasional',
            'excerpt' => 'Dalam rangka mengenalkan sejarah dan budaya Indonesia, siswa-siswi kelas besar mengunjungi Museum Nasional Jakarta untuk belajar tentang berbagai koleksi bersejarah.',
            'content' => 'Dalam rangka mengenalkan sejarah dan budaya Indonesia, siswa-siswi kelas besar mengunjungi Museum Nasional Jakarta untuk belajar tentang berbagai koleksi bersejarah.',
            'author_name' => 'Pak Budi Santoso',
            'created_at' => '2025-11-15 08:45:00',
            'featured_image' => '/images/activity_logo.png'
        ]
    ];
}
?>

<!-- Admin Navbar -->
<?php component('admin-navbar', ['title' => 'Artikel']); ?>

<!-- Articles Card -->
<div class="mt-4">
    <div class="w-full bg-white-neutral border border-white-neutral rounded-[16px] px-[24px] py-[16px]">
        <div class="flex items-center justify-between">
            <!-- Title and Description -->
            <div class="flex flex-col items-start">
                <h3 class="font-bold text-[16px] leading-[21px] text-black-soft mb-[8px]">Daftar Artikel</h3>
                <p class="font-normal text-[14px] leading-[21px] text-white-soft">Artikel yang telah dibuat akan muncul di sini</p>
            </div>
            
            <!-- Add Article Button -->
            <div class="flex-shrink-0">
                <?php component('button', [
                    'variant' => '8',
                    'icon' => 'plus',
                    'text' => 'Tambah Artikel/Berita',
                    'type' => 'button',
                    'id' => 'btn-add-article',
                    'attrs' => [
                        'onclick' => 'window.location.href="' . url('/admin/articles/create') . '"'
                    ]
                ]); ?>
            </div>
        </div>
        
        <!-- Articles List -->
        <div class="mt-5">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <?php component('widget/articles/articles_list_data', [
                        'id' => $article['id'],
                        'title' => $article['title'],
                        'excerpt' => $article['excerpt'] ?? substr(strip_tags($article['content']), 0, 150),
                        'author' => $article['author_name'],
                        'date' => $article['created_at'],
                        'image' => $article['featured_image'] ?? ''
                    ]); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8 text-white-shadow">
                    <p class="font-normal text-[14px]">Belum ada artikel. Klik "Tambah Artikel/Berita" untuk membuat artikel baru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
