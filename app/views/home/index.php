<?php 
$pageTitle = 'Home';
ob_start(); 

// Data keunggulan sekolah
$keunggulanItems = [
    [
        'image' => 'card1_telescope.png',
        'title' => 'Guru Bersertifikat Montessori',
        'description' => 'Pembelajaran dengan pendekatan personal sesuai karakter anak.'
    ],
    [
        'image' => 'card2_car.png',
        'title' => 'Kegiatan Interaktif & Kreatif',
        'description' => 'Setiap hari anak belajar lewat pengalaman langsung.'
    ],
    [
        'image' => 'card3_hourse.png',
        'title' => 'Fasilitas Aman & Nyaman',
        'description' => 'Ruang belajar bersih, area bermain luas, dan lingkungan positif.'
    ],
    [
        'image' => 'card4_love.png',
        'title' => 'Pendekatan Karakter & Empati',
        'description' => 'Fokus pada pembentukan karakter sejak dini.'
    ],
];
?>

<!-- Hero Section -->
<div class="mt-[52px]">
    <?php component('widget/home_hero'); ?>
</div>

<!-- Keunggulan Sekolah Section -->
<section class="mt-[120px]">
    <div class="container mx-auto">
        <?php component('badge', ['text' => 'Keunggulan Sekolah']); ?>
        
        <!-- Cards Grid -->
        <div class="relative">
            <!-- Vector decoration -->
            <img 
                src="<?= url('/images/vectors/vector_keunggulan.png') ?>" 
                alt="" 
                class="absolute -top-[76px] -right-4 w-[68px] h-[74px] -rotate-6 pointer-events-none z-10"
            >
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-[32px]">
                <?php foreach ($keunggulanItems as $item): ?>
                    <?php component('widget/card_keunggulan', [
                        'image' => $item['image'],
                        'title' => $item['title'],
                        'description' => $item['description']
                    ]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="mt-[120px] relative">
    <!-- Vector About (floating between sections) -->
    <img 
        src="<?= url('/images/vectors/vector_about.png') ?>" 
        alt="" 
        class="absolute -top-[120px] left-1/2 -translate-x-1/2 w-[143px] h-[143px] pointer-events-none z-10"
    >
    
    <div class="container mx-auto relative">
        <?php component('widget/home_about'); ?>
        
        <!-- Vector Pita (floating at bottom right of container) -->
        <img 
            src="<?= url('/images/vectors/vector_pita.png') ?>" 
            alt="" 
            class="absolute -bottom-[50px] -right-[10px] w-[140px] h-[110px] pointer-events-none z-10"
        >
    </div>
</section>

<!-- Program Sekolah Section -->
<section class="mt-[120px] relative">
    <!-- Vector Program (floating between sections) -->
    <img 
        src="<?= url('/images/vectors/vector_program.png') ?>" 
        alt="" 
        class="absolute -top-[60px] left-1/2 -translate-x-[calc(50%+50px)] w-[280px] h-[190px] pointer-events-none z-10"
    >
    
    <div class="container mx-auto relative">
        <?php component('badge', ['text' => 'Program Sekolah']); ?>
        
        <!-- Program Grid -->
        <div class="mt-[32px]">
            <?php component('widget/home_program'); ?>
        </div>
    </div>
</section>

<!-- Testimoni Orang Tua Siswa Section -->
<section class="mt-[120px]">
    <div class="container mx-auto">
        <?php component('badge', ['text' => 'Testimoni Orang Tua Siswa']); ?>
        
        <div class="mt-[32px]">
            <?php component('widget/home_testimoni'); ?>
        </div>
    </div>
</section>

<!-- Kegiatan Section -->
<section class="mt-[120px]">
    <div class="container mx-auto">
        <?php component('badge', ['text' => 'Kegiatan yang Akan Datang']); ?>
        
        <div class="mt-[32px] flex flex-col gap-[24px]">
            <?php component('widget/home_kegiatan', [
                'tanggal' => '20',
                'bulan' => 'NOV',
                'tahun' => '2025',
                'nama_kegiatan' => 'Workshop Montessori untuk Orang Tua',
                'jam' => '08:00 - 12:00',
                'tempat' => 'Aula Zivana Montessori',
                'status' => 'public'
            ]); ?>
            
            <?php component('widget/home_kegiatan', [
                'tanggal' => '25',
                'bulan' => 'NOV',
                'tahun' => '2025',
                'nama_kegiatan' => 'Field Trip ke Kebun Binatang',
                'jam' => '07:30 - 14:00',
                'tempat' => 'Taman Safari Indonesia',
                'status' => 'private'
            ]); ?>
            
            <?php component('widget/home_kegiatan', [
                'tanggal' => '05',
                'bulan' => 'DES',
                'tahun' => '2025',
                'nama_kegiatan' => 'Pentas Seni Akhir Semester',
                'jam' => '09:00 - 11:30',
                'tempat' => 'Gedung Serbaguna Zivana',
                'status' => 'public'
            ]); ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="mt-[120px] relative z-10">
    <div class="container mx-auto">
        <?php component('badge', ['text' => 'Frequently Ask Questions (FAQs)']); ?>
        
        <div class="mt-[32px]">
            <?php component('widget/home_faq', [
                'faqs' => [
                    [
                        'question' => 'Apa itu metode Montessori?',
                        'answer' => 'Metode Montessori adalah pendekatan pendidikan yang dikembangkan oleh Dr. Maria Montessori. Metode ini menekankan pembelajaran mandiri, eksplorasi, dan pengembangan kreativitas anak melalui lingkungan yang disiapkan khusus.'
                    ],
                    [
                        'question' => 'Berapa usia minimal untuk mendaftar di Zivana Montessori?',
                        'answer' => 'Kami menerima anak mulai usia 2 tahun untuk program Toddler dan usia 3-6 tahun untuk program Primary/Casa.'
                    ],
                    [
                        'question' => 'Apakah guru-guru di Zivana Montessori bersertifikat?',
                        'answer' => 'Ya, semua guru kami telah mengikuti pelatihan Montessori dan memiliki sertifikasi resmi dari lembaga pelatihan Montessori yang terakreditasi.'
                    ],
                    [
                        'question' => 'Bagaimana cara mendaftar di Zivana Montessori?',
                        'answer' => 'Anda dapat mendaftar dengan mengisi formulir pendaftaran online di website kami atau datang langsung ke sekolah untuk konsultasi dan tour fasilitas.'
                    ],
                    [
                        'question' => 'Apakah tersedia program untuk anak berkebutuhan khusus?',
                        'answer' => 'Kami menyediakan pendekatan inklusif dan dapat menyesuaikan program untuk anak dengan kebutuhan khusus. Silakan hubungi kami untuk konsultasi lebih lanjut mengenai kebutuhan spesifik anak Anda.'
                    ],
                ]
            ]); ?>
        </div>
    </div>
</section>

<?php component('footer', [
    'showCta' => true,
    'ctaTitle' => 'Daftar Sekarang',
    'ctaDescription' => 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami<br>di Zivana Montessori School',
    'ctaButtonText' => 'Daftar ke Sekolah',
    'ctaButtonHref' => '#'
]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
