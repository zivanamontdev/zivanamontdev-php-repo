<?php
/**
 * Home Hero Widget Component
 * 
 * Hero section for the home/beranda page
 * Displays as a large card with primary background
 */
?>

<section class="w-full lg:container mx-auto px-5 mt-[24px] mb-[40px] md:mt-[52px] md:mb-[120px]">
    <div class="bg-primary rounded-[32px] h-auto min-h-[400px] md:h-[544px] py-[32px] px-[20px] md:py-[56px] md:px-[64px] relative overflow-hidden">
        <!-- Background mask with gradient opacity (right to left fade) -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= asset('images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Hero content -->
        <div class="relative z-10 h-full flex flex-col justify-center md:justify-between gap-8 md:gap-0">
            <!-- Title with vector background -->
            <div class="relative">
                <!-- Vector behind title -->
                <img 
                    src="<?= asset('images/vectors/vector_hero_home.png') ?>" 
                    alt="" 
                    class="absolute left-1/2 -translate-x-1/2 top-[100px] md:top-[2px] w-[238px] h-[94px] md:left-auto md:translate-x-0 md:right-[200px] md:w-[504px] md:h-[120px] pointer-events-none"
                >
                
                <h1 class="text-white-neutral font-bold text-[32px] md:text-[68px] leading-[110%] md:leading-[140%] text-center md:text-left relative z-10">
                    Sekolah Berbasis Montessori Islami<br>
                    <span class="inline-block mt-[10px]">
                        Untuk Semua Anak
                    </span>
                </h1>
                
                <!-- Description -->
                <p class="text-white-neutral font-normal text-[16px] md:text-[24px] leading-[150%] md:leading-[38px] text-center md:text-left mt-6 md:mt-11 relative z-10">
                    Mengembangkan potensi anak secara alami melalui<br>
                    metode Montessori yang menumbuhkan kemandirian,<br>
                    rasa ingin tahu, dan kecintaan belajar.
                </p>
            </div>
            
            <!-- Button -->
            <div class="flex justify-center md:justify-start mt-8 md:mt-0">
                <?php component('button', [
                    'text' => 'Daftar Sekarang',
                    'variant' => '6',
                    'href' => url('/registration')
                ]); ?>
            </div>
        </div>
    </div>
</section>
