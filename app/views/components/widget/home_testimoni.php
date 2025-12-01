<?php
/**
 * Home Testimoni Widget
 * 
 * Testimonial section with 3 cards layout
 */
?>

<div class="flex gap-[24px]">
    <!-- Card 1 - Left (Full height) -->
    <div class="flex-1 h-[488px] bg-white-neutral rounded-[24px] p-[24px] relative flex flex-col overflow-hidden">
        <!-- Vector Star -->
        <img 
            src="<?= url('/images/vectors/vector_star1.png') ?>" 
            alt="" 
            class="absolute top-0 -right-[50px] w-[230px] h-[230px] pointer-events-none z-0"
        >
        
        <!-- Title -->
        <div class="h-[194px] mb-[24px] relative z-10">
            <h3 class="font-bold text-[32px] leading-[140%] text-black-soft">
                Anak jadi lebih mandiri, percaya diri. Guru sabar dan selalu update perkembangan anak.
            </h3>
        </div>
        
        <!-- Testimonial Text -->
        <div class="bg-white-secondary rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[24px]">
            <p class="font-normal text-[20px] leading-[160%] text-black-neutral">
                Sejak masuk Zivana Montessori, Aira jadi jauh lebih mandiri. Dia sekarang bisa beresin barang sendiri dan lebih percaya diri kalau diminta coba hal baru. Gurunya sabar banget dan selalu kasih update perkembangan anak.
            </p>
        </div>
        
        <!-- Profile -->
        <div class="flex items-center mt-auto">
            <img 
                src="<?= url('/images/image_testi.jpg') ?>" 
                alt="Rani" 
                class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
            >
            <div>
                <p class="font-bold text-[16px] text-black-neutral mb-[4px]">Rani</p>
                <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari Aira</p>
            </div>
        </div>
    </div>
    
    <!-- Right Section - 2 cards stacked vertically -->
    <div class="flex-1 flex flex-col gap-[24px]">
        <!-- Card 2 - Top -->
        <div class="flex-1 bg-secondary rounded-[24px] p-[24px] relative overflow-hidden">
            <!-- Vector Star 2 -->
            <img 
                src="<?= url('/images/vectors/vector_star2.png') ?>" 
                alt="" 
                class="absolute top-0 right-0 w-[150px] h-[150px] pointer-events-none z-0"
            >
            <!-- Content placeholder -->
        </div>
        
        <!-- Card 3 - Bottom -->
        <div class="flex-1 bg-white-neutral rounded-[24px] p-[24px] relative overflow-hidden">
            <!-- Vector Star 3 -->
            <img 
                src="<?= url('/images/vectors/vector_star3.png') ?>" 
                alt="" 
                class="absolute bottom-0 right-0 w-[150px] h-[150px] pointer-events-none z-0"
            >
            <!-- Content placeholder -->
        </div>
    </div>
</div>
