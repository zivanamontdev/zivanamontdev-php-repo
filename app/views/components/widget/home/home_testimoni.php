<?php
/**
 * Home Testimoni Widget
 * 
 * Testimonial section with 3 cards layout
 * 
 * @param array $testimonials - Array of testimonials from database
 */

$testimonials = $testimonials ?? [];

// Prepare testimonial data with defaults
$testi1 = $testimonials[0] ?? [
    'parent_name' => 'Rani',
    'child_name' => 'Aira',
    'highlight_text' => 'Anak jadi lebih mandiri, percaya diri. Guru sabar dan selalu update perkembangan anak.',
    'testimonial_text' => 'Sejak masuk Zivana Montessori, Aira jadi jauh lebih mandiri. Dia sekarang bisa beresin barang sendiri dan lebih percaya diri kalau diminta coba hal baru. Gurunya sabar banget dan selalu kasih update perkembangan anak.',
    'image' => null
];
$testi2 = $testimonials[1] ?? [
    'parent_name' => 'Andi',
    'child_name' => 'Bima',
    'highlight_text' => '',
    'testimonial_text' => 'Pendekatannya yang tidak memaksa anak. Bima belajar sambil bermain, tapi hasilnya kelihatan banget. Dia jadi lebih fokus dan punya banyak kosa kata baru yang dia dapat dari aktivitas harian.',
    'image' => null
];
$testi3 = $testimonials[2] ?? [
    'parent_name' => 'Selvi',
    'child_name' => 'Mika',
    'highlight_text' => '',
    'testimonial_text' => 'Lingkungannya aman, bersih, dan nyaman. Anak saya betah banget di sekolah. Setiap pulang, Mika selalu cerita kegiatan seru yang dia lakukan. Kami merasa sekolah ini benar-benar peduli sama tiap anak satu per satu.',
    'image' => null
];

// Get image URLs
$imageUrl1 = !empty($testi1['image']) ? url('/uploads/testimonials/' . $testi1['image']) : url('/images/image_testi.jpg');
$imageUrl2 = !empty($testi2['image']) ? url('/uploads/testimonials/' . $testi2['image']) : url('/images/image_testi.jpg');
$imageUrl3 = !empty($testi3['image']) ? url('/uploads/testimonials/' . $testi3['image']) : url('/images/image_testi.jpg');
?>

<div class="flex flex-col lg:flex-row gap-[24px]">
    <!-- Card 1 - Left (Full height) -->
    <div class="w-full lg:flex-1 h-auto min-h-[400px] lg:h-[488px] bg-white-neutral rounded-[24px] p-[20px] md:p-[24px] relative flex flex-col overflow-hidden">
        <!-- Vector Star -->
        <img 
            src="<?= url('/images/vectors/vector_star1.png') ?>" 
            alt="" 
            class="absolute top-0 -right-[50px] w-[230px] h-[230px] pointer-events-none z-0"
        >
        
        <!-- Title -->
        <div class="h-auto lg:h-[194px] mb-[16px] md:mb-[24px] relative z-10">
            <h3 class="font-bold text-[24px] md:text-[32px] leading-[140%] text-black-soft">
                <?= !empty($testi1['highlight_text']) ? e($testi1['highlight_text']) : e($testi1['testimonial_text']) ?>
            </h3>
        </div>
        
        <!-- Testimonial Text -->
        <div class="bg-white-secondary rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[16px] md:mb-[24px] relative z-10">
            <p class="font-normal text-[16px] md:text-[20px] leading-[160%] text-black-neutral">
                <?= e($testi1['testimonial_text']) ?>
            </p>
        </div>
        
        <!-- Profile -->
        <div class="flex items-center mt-auto relative z-10">
            <img 
                src="<?= $imageUrl1 ?>" 
                alt="<?= e($testi1['parent_name']) ?>" 
                class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22%3E%3Ccircle cx=%2222%22 cy=%2222%22 r=%2222%22 fill=%22%23E0E0E0%22/%3E%3Cpath d=%22M22,10 a5,5 0 1,0 0,10 a5,5 0 1,0 0,-10 M22,25 a10,8 0 0,0 -10,8 h20 a10,8 0 0,0 -10,-8%22 fill=%22%23999%22/%3E%3C/svg%3E';"
            >
            <div>
                <p class="font-bold text-[16px] text-black-neutral mb-[4px]"><?= e($testi1['parent_name']) ?></p>
                <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari <?= e($testi1['child_name']) ?></p>
            </div>
        </div>
    </div>
    
    <!-- Right Section - 2 cards stacked vertically -->
    <div class="w-full lg:flex-1 flex flex-col gap-[24px]">
        <!-- Card 2 - Top -->
        <div class="flex-1 bg-secondary rounded-[24px] p-[24px] relative overflow-hidden flex flex-col">
            <!-- Vector Star 2 -->
            <img 
                src="<?= url('/images/vectors/vector_star2.png') ?>" 
                alt="" 
                class="absolute top-0 right-0 w-[150px] h-[150px] pointer-events-none z-0"
            >
            
            <!-- Testimonial Text -->
            <div class="bg-[#F5B746] rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[16px] relative z-10">
                <p class="font-normal text-[16px] leading-[170%] text-black-soft">
                    <?= e($testi2['testimonial_text']) ?>
                </p>
            </div>
            
            <!-- Profile -->
            <div class="flex items-center mt-auto relative z-10">
                <img 
                    src="<?= $imageUrl2 ?>" 
                    alt="<?= e($testi2['parent_name']) ?>" 
                    class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22%3E%3Ccircle cx=%2222%22 cy=%2222%22 r=%2222%22 fill=%22%23E0E0E0%22/%3E%3Cpath d=%22M22,10 a5,5 0 1,0 0,10 a5,5 0 1,0 0,-10 M22,25 a10,8 0 0,0 -10,8 h20 a10,8 0 0,0 -10,-8%22 fill=%22%23999%22/%3E%3C/svg%3E';"
                >
                <div>
                    <p class="font-bold text-[16px] text-black-neutral mb-[4px]"><?= e($testi2['parent_name']) ?></p>
                    <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari <?= e($testi2['child_name']) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Card 3 - Bottom -->
        <div class="flex-1 bg-white-neutral rounded-[24px] p-[24px] relative overflow-hidden flex flex-col">
            <!-- Vector Star 3 -->
            <img 
                src="<?= url('/images/vectors/vector_star3.png') ?>" 
                alt="" 
                class="absolute bottom-0 right-0 w-[150px] h-[150px] pointer-events-none z-0"
            >
            
            <!-- Testimonial Text -->
            <div class="bg-white-secondary rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[16px] relative z-10">
                <p class="font-normal text-[16px] leading-[170%] text-black-soft">
                    <?= e($testi3['testimonial_text']) ?>
                </p>
            </div>
            
            <!-- Profile -->
            <div class="flex items-center mt-auto relative z-10">
                <img 
                    src="<?= $imageUrl3 ?>" 
                    alt="<?= e($testi3['parent_name']) ?>" 
                    class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22%3E%3Ccircle cx=%2222%22 cy=%2222%22 r=%2222%22 fill=%22%23E0E0E0%22/%3E%3Cpath d=%22M22,10 a5,5 0 1,0 0,10 a5,5 0 1,0 0,-10 M22,25 a10,8 0 0,0 -10,8 h20 a10,8 0 0,0 -10,-8%22 fill=%22%23999%22/%3E%3C/svg%3E';"
                >
                <div>
                    <p class="font-bold text-[16px] text-black-neutral mb-[4px]"><?= e($testi3['parent_name']) ?></p>
                    <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari <?= e($testi3['child_name']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
