<?php
/**
 * Profile Section Widget Component
 * 
 * Section 1: Tentang Sekolah
 * Layout 1:1 - Image kiri, Card kanan
 */
?>

<section class="container mx-auto">
    <div class="flex gap-[32px]">
        <!-- Section A: Image -->
        <div class="flex-1">
            <div class="h-[527px] rounded-[16px] overflow-hidden">
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
            <div class="bg-white-neutral rounded-[12px] px-[24px] py-[16px] h-[455px] relative overflow-hidden">
                <!-- Title -->
                <h3 class="font-bold text-[24px] leading-[38px] text-black-soft mb-[16px]">
                    Sejarah Singkat/Prakata Kepala Sekolah
                </h3>
                
                <!-- Description -->
                <p class="font-normal text-[20px] leading-[38px] text-black-soft h-[365px] overflow-hidden">
                    TK Zivana Montessori didirikan pada tahun 2021 di bawah naungan Yayasan Zivana Insan Mandiri. TK Zivana Montessori  merupakan salah satu satuan pendidikan non formal yang terletak di daerah perkotaan. Lokasi satuan pendidikan agak jauh dari jalan raya, sehingga satuan pendidikan dan masyarakat sekitar aman dan tidak terganggu oleh hiruk pikuk dan kebisingan lalu lintas di perkotaan. Tokoh yang paling berjasa dalam lahirnya TK Zivana Montessori yakni Ibu Adilah Wina Fitria.
                </p>
                
                <!-- Vector Profile - Bottom Right -->
                <img 
                    src="<?= url('images/vectors/vector_profile.png') ?>" 
                    alt="" 
                    class="absolute bottom-0 right-0 w-[180px] h-auto pointer-events-none"
                >
            </div>
            
            <!-- Button -->
            <div class="mt-[20px]">
                <?php component('button', ['text' => 'Daftar Sekarang', 'variant' => '1', 'href' => url('/registration')]); ?>
            </div>
        </div>
    </div>
</section>
