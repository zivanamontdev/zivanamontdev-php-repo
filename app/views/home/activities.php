<?php 
$pageTitle = 'School Activities';
ob_start(); 
?>

<!-- Page Header -->
<section class="container mx-auto mt-[52px]">
    <div class="bg-primary rounded-[32px] h-[132px] p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[40px] leading-[100%] text-white-neutral text-center">
            Aktivitas dan Pembelajaran Sekolah
        </h1>
    </div>
</section>

<!-- Kurikulum Sekolah Section -->
<section class="container mx-auto mt-[80px]">
    <div class="flex justify-center mb-[32px]">
        <?php component('badge', ['text' => 'Kurikulum Sekolah']); ?>
    </div>
    
    <?php component('widget/activities_kurikulum'); ?>
</section>

<!-- Kelas-kelas Section -->
<section class="container mx-auto mt-[80px]">
    <div class="flex justify-center mb-[32px]">
        <?php component('badge', ['text' => 'Kelas-kelas']); ?>
    </div>
    
    <?php 
    $kelasData = [
        [
            'image' => 'image_kelas_1.png',
            'title' => 'Toddler Class',
            'usia' => '1.5 - 3 tahun',
            'durasi' => '2 Jam',
            'jumlah_murid' => '15 anak/kelas'
        ],
        [
            'image' => 'image_kelas_2.png',
            'title' => 'Preschool Class',
            'usia' => '3 - 4 tahun',
            'durasi' => '3 Jam',
            'jumlah_murid' => '18 anak/kelas'
        ],
        [
            'image' => 'image_kelas_3.png',
            'title' => 'Kindergarten Class',
            'usia' => '4 - 6 tahun',
            'durasi' => '4 Jam',
            'jumlah_murid' => '20 anak/kelas'
        ]
    ];
    component('widget/activities_kelas', ['kelas' => $kelasData]); 
    ?>
</section>

<!-- Program Tahun Ajaran Section -->
<section class="container mx-auto mt-[80px]">
    <div class="flex justify-center mb-[24px]">
        <?php component('badge', ['text' => 'Program Tahun Ajaran']); ?>
    </div>
    
    <?php 
    $programData = [
        [
            'image' => 'activities_images_1.png',
            'title' => 'Puncak Tema Semester 1',
            'description' => 'Kegiatan penutup tema pembelajaran semester pertama dengan berbagai aktivitas menyenangkan.'
        ],
        [
            'image' => 'activities_images_2.png',
            'title' => 'Field Trip Edukatif',
            'description' => 'Kunjungan belajar ke berbagai tempat menarik untuk memperluas wawasan anak.'
        ],
        [
            'image' => 'activities_images_3.png',
            'title' => 'Pentas Seni Tahunan',
            'description' => 'Ajang kreativitas dan bakat anak-anak dalam seni musik, tari dan drama.'
        ],
        [
            'image' => 'activities_images_4.png',
            'title' => 'Cooking Class',
            'description' => 'Kegiatan memasak bersama untuk melatih kemandirian dan kreativitas anak.'
        ],
        [
            'image' => 'activities_images_5.png',
            'title' => 'Graduation Ceremony',
            'description' => 'Upacara kelulusan yang meriah untuk merayakan pencapaian anak-anak.'
        ]
    ];
    component('widget/activities_card', ['programs' => $programData, 'floatingVector' => 'vector_highlight_program_tahun.png']); 
    ?>
    
    <!-- Tampilkan Lebih Banyak Button -->
    <div class="flex justify-center mt-[32px] mb-[80px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'href' => '#']); ?>
    </div>
    
    <!-- Program Harian Sekolah Badge -->
    <div class="flex justify-start mb-[32px]">
        <?php component('badge', ['text' => 'Program Harian Sekolah']); ?>
    </div>
    
    <?php 
    $programHarianData = [
        [
            'image' => 'activities_images_1.png',
            'title' => 'Morning Circle Time',
            'description' => 'Kegiatan pagi untuk menyambut hari dengan doa, lagu, dan berbagi cerita bersama.'
        ],
        [
            'image' => 'activities_images_2.png',
            'title' => 'Sensory Play',
            'description' => 'Aktivitas bermain sensorik untuk mengembangkan kreativitas dan motorik halus anak.'
        ],
        [
            'image' => 'activities_images_3.png',
            'title' => 'Outdoor Activities',
            'description' => 'Kegiatan luar ruangan untuk melatih motorik kasar dan eksplorasi alam.'
        ],
        [
            'image' => 'activities_images_4.png',
            'title' => 'Story Time',
            'description' => 'Waktu bercerita untuk mengembangkan imajinasi dan kecintaan membaca.'
        ],
        [
            'image' => 'activities_images_5.png',
            'title' => 'Art & Craft',
            'description' => 'Kegiatan seni dan kerajinan untuk mengekspresikan kreativitas anak.'
        ]
    ];
    component('widget/activities_card', ['programs' => $programHarianData, 'floatingVectorCenter' => 'vector_highlight_program_harian.png']); 
    ?>
</section>

<!-- School Schedule Section -->
<?php if (!empty($schedules)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Daily School Schedule</h2>
        
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="divide-y">
                <?php foreach ($schedules as $schedule): ?>
                    <div class="p-6 hover:bg-purple-50 transition">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-32">
                                <span class="text-purple-600 font-bold text-lg">
                                    <?= date('H:i', strtotime($schedule['time_start'])) ?>
                                    <?php if ($schedule['time_end']): ?>
                                        - <?= date('H:i', strtotime($schedule['time_end'])) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="ml-6">
                                <h4 class="font-bold text-lg mb-1"><?= e($schedule['activity_name']) ?></h4>
                                <?php if ($schedule['description']): ?>
                                    <p class="text-gray-600"><?= e($schedule['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
