<?php 
$pageTitle = 'Home';
ob_start(); 

// Data keunggulan sekolah
$keunggulanItems = [
    [
        'image' => 'card1_islami.png',
        'title' => 'Montessori Islami',
        'description' => 'Membangun kemandirian dan akhlak mulia sejak dini melalui perpaduan nilai Islam dan Montessori.'
    ],
    [
        'image' => 'card2_flower.png',
        'title' => 'Inklusif',
        'description' => 'Menerima berbagai keberagaman anak, serta memberikan dukungan sesuai kebutuhannya.'
    ],
    [
        'image' => 'card3_hourse.png',
        'title' => 'Pendekatan Neurosensory',
        'description' => 'Meningkatkan fokus, regulasi emosi, dan kesiapan belajar melalui stimulasi sensorik & hidroterapi.'
    ],
    [
        'image' => 'card4_love.png',
        'title' => 'Berkarakter & Peduli Lingkungan',
        'description' => 'Mandiri, empati, bertanggung jawab & cinta ciptaan Allah SWT melalui praktik nyata sehari-hari.'
    ],
];
?>

<!-- Hero Section -->
<?php component('widget/home/home_hero'); ?>

<!-- Keunggulan Sekolah Section -->
<section>
    <div class="w-full lg:container mx-auto px-5">
        <div class="flex justify-center md:justify-start">
            <?php component('badge', ['text' => 'Keunggulan Sekolah']); ?>
        </div>
        
        <!-- Cards Grid -->
        <div class="relative">
            <!-- Vector decoration -->
            <img 
                src="<?= asset('images/vectors/vector_keunggulan.png') ?>" 
                alt="" 
                class="hidden md:block absolute -top-[76px] -right-4 w-[68px] h-[74px] -rotate-6 pointer-events-none z-10"
            >
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-[32px]">
                <?php foreach ($keunggulanItems as $index => $item): ?>
                    <?php component('widget/home/home_card_keunggulan', [
                        'image' => $item['image'],
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'bgColor' => ($index === 1) ? 'bg-[#FEDBA9]' : 'bg-white-neutral',
                        'isFirst' => ($index === 0)
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
        src="<?= asset('images/vectors/vector_about.png') ?>" 
        alt="" 
        class="hidden md:block absolute -top-[120px] left-1/2 -translate-x-1/2 w-[143px] h-[143px] pointer-events-none z-10"
    >
    
    <div class="w-full lg:container mx-auto px-5 relative">
        <?php component('widget/home/home_about'); ?>
        
        <!-- Vector Pita (floating at bottom right of container) -->
        <img 
            src="<?= asset('images/vectors/vector_pita.png') ?>" 
            alt="" 
            class="hidden md:block absolute -bottom-[50px] -right-[10px] w-[140px] h-[110px] pointer-events-none z-10"
        >
    </div>
</section>

<!-- Program Sekolah Section -->
<section class="mt-[120px] relative">
    <!-- Vector Program (floating between sections) -->
    <img 
        src="<?= asset('images/vectors/vector_program.png') ?>" 
        alt="" 
        class="hidden md:block absolute -top-[60px] left-1/2 -translate-x-[calc(50%+50px)] w-[280px] h-[190px] pointer-events-none z-0"
    >
    
    <div class="w-full lg:container mx-auto px-5 relative">
        <div class="flex justify-center md:justify-start relative z-10">
            <?php component('badge', ['text' => 'Program Sekolah']); ?>
        </div>
        
        <!-- Program Grid -->
        <div class="mt-[32px]">
            <?php if (!empty($highlightPrograms)): ?>
                <?php component('widget/home/home_program', ['highlightPrograms' => $highlightPrograms]); ?>
            <?php else: ?>
                <!-- Empty State Placeholder -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">
                    <div class="bg-white-neutral p-[16px] rounded-[20px] flex flex-col h-full opacity-60">
                        <!-- Image Placeholder -->
                        <div class="w-full h-[184px] rounded-[16px] overflow-hidden mb-[16px] flex-shrink-0 bg-gray-placeholder relative flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="font-bold text-[20px] leading-[32px] md:text-[24px] md:leading-[38px] text-gray-400 mb-[16px]">
                            Belum Ada Program Sekolah
                        </h3>
                        
                        <!-- Description -->
                        <p class="font-normal text-[16px] leading-[28px] md:text-[20px] md:leading-[32px] text-gray-400 flex-grow">
                            Program sekolah akan ditampilkan di sini setelah data ditambahkan.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
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
                <!-- Empty State Placeholder -->
                <div class="bg-white-neutral p-[24px] rounded-[20px] flex items-start gap-[24px] opacity-60">
                    <!-- Date Section Placeholder -->
                    <div class="flex-shrink-0 flex flex-col items-center justify-center w-[96px] h-[112px] rounded-[12px] bg-gray-placeholder">
                        <span class="text-gray-400 font-bold text-[32px] leading-[40px]">--</span>
                        <span class="text-gray-400 font-bold text-[14px] leading-[20px] mt-1">---</span>
                        <span class="text-gray-400 font-normal text-[12px] leading-[16px]">----</span>
                    </div>
                    
                    <!-- Content Section Placeholder -->
                    <div class="flex-grow">
                        <h3 class="font-bold text-[20px] md:text-[24px] leading-[32px] md:leading-[38px] text-gray-400 mb-[12px]">
                            Belum Ada Kegiatan Dijadwalkan
                        </h3>
                        <p class="font-normal text-[16px] md:text-[18px] leading-[28px] text-gray-400">
                            Kegiatan sekolah akan ditampilkan di sini setelah data ditambahkan.
                        </p>
                    </div>
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
                        'status' => $status,
                        'url' => $event['url'] ?? null
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
            <?php if (!empty($faqs)): ?>
                <?php component('widget/home/home_faq', ['faqs' => $faqs]); ?>
            <?php else: ?>
                <!-- Empty State Placeholder -->
                <div class="bg-white-neutral rounded-[20px] p-[24px] opacity-60">
                    <div class="flex items-start gap-[16px]">
                        <!-- Question Icon Placeholder -->
                        <div class="flex-shrink-0 w-[32px] h-[32px] rounded-full bg-gray-placeholder flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow">
                            <h3 class="font-bold text-[18px] md:text-[20px] leading-[28px] md:leading-[32px] text-gray-400 mb-[8px]">
                                Belum Ada FAQ
                            </h3>
                            <p class="font-normal text-[14px] md:text-[16px] leading-[24px] md:leading-[28px] text-gray-400">
                                Pertanyaan yang sering diajukan akan ditampilkan di sini setelah data ditambahkan.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php component('footer', [
    'showCta' => true,
    'ctaTitle' => 'Daftar Sekarang',
    'ctaDescription' => 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami di Zivana Montessori School',
    'ctaButtonText' => 'Daftar ke Sekolah',
    'ctaButtonHref' => url('/registration')
]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
