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
    <?php component('widget/home/home_hero'); ?>
</div>

<!-- Keunggulan Sekolah Section -->
<section class="mt-[120px]">
    <div class="w-full lg:container mx-auto px-5">
        <div class="flex justify-center md:justify-start">
            <?php component('badge', ['text' => 'Keunggulan Sekolah']); ?>
        </div>
        
        <!-- Cards Grid -->
        <div class="relative">
            <!-- Vector decoration -->
            <img 
                src="<?= url('/images/vectors/vector_keunggulan.png') ?>" 
                alt="" 
                class="absolute -top-[76px] -right-4 w-[68px] h-[74px] -rotate-6 pointer-events-none z-10"
            >
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-[32px]">
                <?php foreach ($keunggulanItems as $index => $item): ?>
                    <?php component('widget/home/home_card_keunggulan', [
                        'image' => $item['image'],
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'bgColor' => ($index === 1) ? 'bg-[#FEDBA9]' : 'bg-white-neutral'
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
    
    <div class="w-full lg:container mx-auto px-5 relative">
        <?php component('widget/home/home_about'); ?>
        
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
        class="absolute -top-[60px] left-1/2 -translate-x-[calc(50%+50px)] w-[280px] h-[190px] pointer-events-none z-0"
    >
    
    <div class="w-full lg:container mx-auto px-5 relative">
        <div class="flex justify-center md:justify-start relative z-10">
            <?php component('badge', ['text' => 'Program Sekolah']); ?>
        </div>
        
        <!-- Program Grid -->
        <div class="mt-[32px]">
            <?php component('widget/home/home_program', ['highlightPrograms' => $highlightPrograms ?? []]); ?>
        </div>
    </div>
</section>

<!-- Testimoni Orang Tua Siswa Section -->
<section class="mt-[120px]">
    <div class="w-full lg:container mx-auto px-5">
        <div class="flex justify-center md:justify-start">
            <?php component('badge', ['text' => 'Testimoni Orang Tua Siswa']); ?>
        </div>
        
        <div class="mt-[32px]">
            <?php component('widget/home/home_testimoni', ['testimonials' => $testimonials ?? []]); ?>
        </div>
    </div>
</section>

<!-- Kegiatan Section -->
<section class="mt-[120px]">
    <div class="w-full lg:container mx-auto px-5">
        <div class="flex justify-center md:justify-start">
            <?php component('badge', ['text' => 'Kegiatan yang Akan Datang']); ?>
        </div>
        
        <div class="mt-[32px] flex flex-col gap-[24px]">
            <?php 
            // Get events from data (limit to 3 for homepage)
            $displayEvents = array_slice($events ?? [], 0, 3);
            
            if (empty($displayEvents)): 
            ?>
                <div class="text-center py-8 text-white-soft">
                    <p>Belum ada kegiatan yang dijadwalkan</p>
                </div>
            <?php else: ?>
                <?php foreach ($displayEvents as $event): 
                    // Parse date
                    $dateObj = DateTime::createFromFormat('Y-m-d', $event['event_date']);
                    $tanggal = $dateObj ? $dateObj->format('d') : '01';
                    $bulan = $dateObj ? strtoupper($dateObj->format('M')) : 'JAN';
                    $tahun = $dateObj ? $dateObj->format('Y') : '2025';
                    
                    // Format time
                    $startTime = substr($event['start_time'], 0, 5);
                    $endTime = substr($event['end_time'], 0, 5);
                    $jam = $startTime . ' - ' . $endTime;
                    
                    // Status
                    $status = !empty($event['is_public']) ? 'public' : 'private';
                ?>
                    <?php component('widget/home/home_kegiatan', [
                        'tanggal' => $tanggal,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'nama_kegiatan' => $event['name'],
                        'jam' => $jam,
                        'tempat' => $event['place'],
                        'status' => $status
                    ]); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="mt-[120px] relative z-10">
    <div class="w-full lg:container mx-auto px-5">
        <div class="flex justify-center md:justify-start">
            <?php component('badge', ['text' => 'Frequently Ask Questions (FAQs)', 'class' => 'text-[14px] md:text-[16px] px-[16px] md:px-[20px] tracking-tighter md:tracking-normal']); ?>
        </div>
        
        <div class="mt-[32px]">
            <?php component('widget/home/home_faq', [
                'faqs' => $faqs ?? []
            ]); ?>
        </div>
    </div>
</section>

<?php component('footer', [
    'showCta' => true,
    'ctaTitle' => 'Daftar Sekarang',
    'ctaDescription' => 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami<br>di Zivana Montessori School',
    'ctaButtonText' => 'Daftar ke Sekolah',
    'ctaButtonHref' => url('/registration')
]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
