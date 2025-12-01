<?php
/**
 * Home Program Sekolah Widget
 * 
 * Grid layout with program cards
 */
?>

<div class="flex gap-[24px] h-[524px]">
    <!-- Left Card - Kegiatan Montessori -->
    <div class="flex-1 relative rounded-[32px] overflow-hidden">
        <!-- Background Image -->
        <img 
            src="<?= url('/images/program 1.jpg') ?>" 
            alt="Kegiatan Montessori" 
            class="absolute inset-0 w-full h-full object-cover"
        >
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
        
        <!-- Content -->
        <div class="relative h-full flex flex-col justify-end p-[20px]">
            <h3 class="font-bold text-[24px] leading-[32px] tracking-[0%] text-white-neutral mb-[16px]">
                Kegiatan Montessori
            </h3>
            <p class="font-normal text-[20px] leading-[32px] tracking-[0%] text-white-neutral">
                Dirancang untuk membantu anak belajar melalui eksplorasi langsung
            </p>
        </div>
    </div>
    
    <!-- Right Section -->
    <div class="flex-1 flex flex-col gap-[24px]">
        <!-- Top Card - Field Trip -->
        <div class="relative rounded-[20px] overflow-hidden h-[250px]">
            <!-- Vector Program 2 (floating on right side) -->
            <img 
                src="<?= url('/images/vectors/vector_program2.png') ?>" 
                alt="" 
                class="absolute top-1/2 -right-[0px] -translate-y-1/2 w-[80px] h-[80px] pointer-events-none z-20"
            >
            
            <!-- Background Image -->
            <img 
                src="<?= url('/images/program 2.jpg') ?>" 
                alt="Field Trip" 
                class="absolute inset-0 w-full h-full object-cover"
            >
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            
            <!-- Content -->
            <div class="relative h-full flex flex-col justify-end p-[20px]">
                <h3 class="font-bold text-[24px] leading-[32px] tracking-[0%] text-white-neutral mb-[16px]">
                    Field Trip
                </h3>
                <p class="font-normal text-[20px] leading-[32px] tracking-[0%] text-white-neutral">
                    Dirancang untuk membantu anak belajar melalui eksplorasi langsung,
                </p>
            </div>
        </div>
        
        <!-- Bottom Section - 2 columns -->
        <div class="flex-1 flex gap-[24px]">
            <!-- Left - Parent Sharing -->
            <div class="flex-1 relative rounded-[20px] overflow-hidden">
                <!-- Background Image -->
                <img 
                    src="<?= url('/images/program 2.jpg') ?>" 
                    alt="Parent Sharing" 
                    class="absolute inset-0 w-full h-full object-cover"
                >
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                
                <!-- Content -->
                <div class="relative h-full flex flex-col justify-end p-[20px]">
                    <h3 class="font-bold text-[24px] leading-[32px] tracking-[0%] text-white-neutral">
                        Parent Sharing
                    </h3>
                </div>
            </div>
            
            <!-- Right - Text & Button -->
            <div class="flex-1 flex flex-col justify-center items-start text-left">
                <p class="font-bold text-[28px] leading-[140%] tracking-[0%] text-black-soft mb-[26px]">
                    Serta banyak program sekolah bermanfaat lainnya!
                </p>
                <?php component('button', [
                    'text' => 'Lihat program sekolah',
                    'variant' => '1',
                    'href' => '#'
                ]); ?>
            </div>
        </div>
    </div>
</div>
