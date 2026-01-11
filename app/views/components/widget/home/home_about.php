<?php
/**
 * Home About Widget Component
 * 
 * About section untuk halaman beranda
 * Menampilkan gambar dengan gradient overlay dan keterangan di samping
 */
?>

<div class="flex flex-col lg:flex-row gap-[24px] md:gap-[50px] items-stretch">
    <!-- Image with gradient overlay -->
    <div class="w-full lg:flex-1 relative rounded-[24px] md:rounded-[52px] overflow-hidden h-[300px] md:h-[464px]">
        <!-- Background Image -->
        <img 
            src="<?= asset('images/home_about.png') ?>" 
            alt="Tentang Zivana Montessori" 
            class="w-full h-full object-cover"
        >
        
        <!-- Gradient Overlay (bottom to top, angled to left) -->
        <div class="absolute inset-0" style="background: linear-gradient(160deg, transparent 0%, rgba(198, 90, 12, 0.79) 60%, #FAA30F 100%);"></div>
    </div>
    
    <!-- Content -->
    <div class="w-full lg:flex-1 flex flex-col justify-center">
        <!-- Title -->
        <h2 class="font-bold text-[24px] md:text-[32px] leading-[140%] tracking-normal text-black-soft text-center md:text-left">
            Belajar Bersama dalam Indahnya Keberagaman
        </h2>
        
        <!-- Description -->
        <p class="mt-[16px] font-normal text-[16px] md:text-[24px] leading-[140%] tracking-normal text-black-soft text-center md:text-left">
            Sejak 2021, kami menghadirkan ruang inklusi di mana anak reguler dan berkebutuhan khusus tumbuh berdampingan. Dipandu metode Montessori Islami untuk mengasah empati dan kemandirian.
        </p>
        
        <!-- Button -->
        <div class="mt-[42px] flex justify-center md:justify-start relative z-20">
            <?php component('button', [
                'text' => 'Lihat cerita tentang kami',
                'variant' => '1',
                'href' => url('/profile')
            ]); ?>
        </div>
    </div>
</div>
