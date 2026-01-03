<?php
/**
 * Profile Section Widget Component
 * 
 * Section 1: Tentang Sekolah
 * Layout 1:1 - Image kiri, Card kanan
 */
?>

<section class="container mx-auto px-5 md:px-0">
    <div class="flex flex-col md:flex-row gap-[24px] md:gap-[32px]">
        <!-- Section A: Image -->
        <div class="flex-1">
            <div class="h-[276px] md:h-[527px] rounded-[16px] overflow-hidden">
                <img 
                    src="<?= url('images/image_profile_section_1.png') ?>" 
                    alt="Zivana Montessori" 
                    class="w-full h-full object-cover object-center"
                >
            </div>
        </div>
        
        <!-- Section B: Card + Button -->
        <div class="flex-1 flex flex-col">
            <!-- Card -->
            <div class="bg-white-neutral rounded-[12px] px-[24px] py-[16px] h-auto md:h-[455px] relative overflow-hidden">
                <!-- Title -->
                <h3 class="font-bold text-[16px] leading-[28px] md:text-[24px] md:leading-[38px] text-black-soft mb-[16px]">
                    <?= htmlspecialchars($prakata['title'] ?? 'Profil Singkat') ?>
                </h3>
                
                <!-- Description -->
                <div class="h-auto md:h-[365px] overflow-hidden font-normal text-[14px] leading-[24px] md:text-[20px] md:leading-[38px] text-black-soft">
                    <?= nl2br(htmlspecialchars($prakata['description'] ?? 'Sejak 2021, Zivana Montessori hadir sebagai sekolah inklusi yang memadukan metode Montessori Islami, Deep Learning, dan pendekatan Neurosensory. Anak reguler dan berkebutuhan khusus belajar bersama untuk mengasah fokus, emosi, dan kemandirian.

Dengan 6 area belajar dan motto CHAMPION (Cerdas, Berakhlak, Mandiri, Peduli, Berorientasi Islam), kami berkomitmen mencetak generasi yang percaya diri, cerdas, dan peduli sesama sesuai fitrah dan kecepatan belajarnya masing-masing.')) ?>
                </div>
                
                <!-- Vector Profile - Bottom Right -->
                <img 
                    src="<?= url('images/vectors/vector_profile.png') ?>" 
                    alt="" 
                    class="absolute bottom-0 right-0 w-[180px] h-auto pointer-events-none"
                >
            </div>
            
            <!-- Button -->
            <div class="mt-[20px] flex justify-center md:justify-start">
                <?php component('button', ['text' => 'Daftar Sekarang', 'variant' => '1', 'href' => url('/registration')]); ?>
            </div>
        </div>
    </div>
</section>
