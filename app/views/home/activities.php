<?php 
$pageTitle = 'School Activities';
ob_start(); 
?>

<!-- Page Header -->
<?php component('page_hero', ['title' => 'Aktivitas dan Pembelajaran Sekolah', 'variant' => 'primary']); ?>

<!-- Kurikulum Sekolah Section -->
<section class="container mx-auto px-5 mt-[80px]">
    <div class="flex justify-center mb-[32px]">
        <?php component('badge', ['text' => 'Kurikulum Sekolah']); ?>
    </div>
    
    <?php component('widget/activities/activities_kurikulum'); ?>
</section>

<!-- Kelas-kelas Section -->
<section class="container mx-auto px-5 mt-[80px]">
    <div class="flex justify-center mb-[32px]">
        <?php component('badge', ['text' => 'Kelas-kelas']); ?>
    </div>
    
    <?php component('widget/activities/activities_kelas', ['kelas' => $kelasData]); ?>
</section>

<!-- Program Tahun Ajaran Section -->
<section class="container mx-auto px-5 mt-[80px]">
    <div class="flex justify-center mb-[24px]">
        <?php component('badge', ['text' => 'Program Tahun Ajaran']); ?>
    </div>
    
    <div class="relative">
        <!-- Floating Vector -->
        <img 
            src="<?= url('images/vectors/vector_highlight_program_tahun.png') ?>" 
            alt="" 
            class="absolute -right-[52px] -top-[55px] w-[64px] h-[70px] z-10 pointer-events-none"
        >
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]" id="program-tahun-grid">
            <?php foreach ($programsTahunData as $index => $program): ?>
            <div class="bg-white-neutral p-[16px] rounded-[20px] flex flex-col h-full program-tahun-item <?= $index >= 6 ? 'hidden' : '' ?>" data-index="<?= $index ?>">
                <!-- Image -->
                <div class="w-full h-[184px] rounded-[16px] overflow-hidden mb-[16px] flex-shrink-0 bg-gray-placeholder relative">
                    <img 
                        src="<?= $program['image'] ?? url('images/placeholder.jpg') ?>" 
                        alt="<?= $program['title'] ?? '' ?>" 
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                    >
                    <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="font-bold text-[20px] leading-[32px] md:text-[24px] md:leading-[38px] text-black-soft mb-[16px]">
                    <?= $program['title'] ?? '' ?>
                </h3>
                
                <!-- Description -->
                <p class="font-normal text-[16px] leading-[28px] md:text-[20px] md:leading-[32px] text-black-soft flex-grow">
                    <?= $program['description'] ?? '' ?>
                </p>
                
                <!-- Button -->
                <div class="mt-auto pt-[30px]">
                    <?php component('button', ['text' => 'Lihat Galeri', 'variant' => '7', 'href' => url('/activities-gallery?program_id=' . $program['id'])]); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Tampilkan Lebih Banyak/Sedikit Button -->
    <?php if (count($programsTahunData) > 6): ?>
    <div class="flex justify-center mt-[32px] mb-[80px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'type' => 'button', 'id' => 'btn-toggle-tahun']); ?>
    </div>
    <?php else: ?>
    <div class="mb-[80px]"></div>
    <?php endif; ?>
    
    <script>
    const totalProgramsTahun = <?= count($programsTahunData) ?>;
    let currentVisibleCount = 6;
    
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('btn-toggle-tahun');
        const allItems = document.querySelectorAll('.program-tahun-item');
        
        if (btnToggle) {
            btnToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (currentVisibleCount < totalProgramsTahun) {
                    // Show more
                    const nextCount = Math.min(currentVisibleCount + 3, totalProgramsTahun);
                    
                    // Show items from currentVisibleCount to nextCount
                    for (let i = currentVisibleCount; i < nextCount; i++) {
                        allItems[i].classList.remove('hidden');
                    }
                    
                    currentVisibleCount = nextCount;
                    
                    // Update button text if all shown
                    if (currentVisibleCount >= totalProgramsTahun) {
                        btnToggle.textContent = 'Tampilkan Lebih Sedikit';
                    }
                } else {
                    // Show less - hide all except first 6
                    for (let i = 6; i < totalProgramsTahun; i++) {
                        allItems[i].classList.add('hidden');
                    }
                    
                    currentVisibleCount = 6;
                    btnToggle.textContent = 'Tampilkan Lebih Banyak';
                }
            });
        }
    });
    </script>
    
    <!-- Program Harian Sekolah Badge -->
    <div class="flex justify-center mb-[32px]">
        <?php component('badge', ['text' => 'Program Harian Sekolah']); ?>
    </div>
    
    <div class="relative">
        <!-- Floating Vector -->
        <img 
            src="<?= url('images/vectors/vector_highlight_program_harian.png') ?>" 
            alt="" 
            class="absolute left-1/2 -translate-x-1/2 -top-[55px] w-[64px] h-[70px] z-10 pointer-events-none"
        >
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px]">
            <?php foreach ($programsHarianData as $index => $program): ?>
            <div class="bg-white-neutral p-[16px] rounded-[20px] flex flex-col h-full">
                <!-- Image -->
                <div class="w-full h-[184px] rounded-[16px] overflow-hidden mb-[16px] flex-shrink-0 bg-gray-placeholder relative">
                    <img 
                        src="<?= $program['image'] ?? url('images/placeholder.jpg') ?>" 
                        alt="<?= $program['title'] ?? '' ?>" 
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                    >
                    <svg class="hidden absolute inset-0 w-16 h-16 m-auto text-white-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="font-bold text-[20px] leading-[32px] md:text-[24px] md:leading-[38px] text-black-soft mb-[16px]">
                    <?= $program['title'] ?? '' ?>
                </h3>
                
                <!-- Description -->
                <p class="font-normal text-[16px] leading-[28px] md:text-[20px] md:leading-[32px] text-black-soft flex-grow">
                    <?= $program['description'] ?? '' ?>
                </p>
                
                <!-- Button -->
                <div class="mt-auto pt-[30px]">
                    <?php component('button', ['text' => 'Lihat Galeri', 'variant' => '7', 'href' => url('/activities-gallery?program_id=' . $program['id'] . '&type=harian')]); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
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
