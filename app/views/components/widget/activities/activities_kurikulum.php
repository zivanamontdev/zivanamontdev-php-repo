<?php
/**
 * Activities Kurikulum Widget Component
 * 
 * Displays curriculum information with cards and description
 */

$kurikulumItems = [
    [
        'width' => 54,
        'height' => 56,
        'title' => 'Kurikulum Nasional dengan Pendekatan Montessori'
    ],
    [
        'width' => 53,
        'height' => 56,
        'title' => 'Kurikulum Iman dan Adab'
    ],
    [
        'width' => 56,
        'height' => 56,
        'title' => 'Program Pembelajaran Individu (PPI)'
    ],
    [
        'width' => 56,
        'height' => 56,
        'title' => 'Pendekatan Neurosensory'
    ]
];
?>

<div class="flex flex-col lg:flex-row gap-[24px]">
    <!-- Section 1: Kurikulum Cards -->
    <div class="flex-1 flex flex-col gap-[20px]">
        <?php foreach ($kurikulumItems as $item): ?>
        <div class="h-auto lg:h-[107px] px-[12px] py-[16px] lg:px-[24px] lg:py-[15px] rounded-[20px] bg-white-neutral flex items-center relative overflow-hidden">
            <!-- Vector Kurikulum - Bottom Right (Rotated 90deg) -->
            <img 
                src="<?= url('images/vectors/vector_kurikulum.png') ?>" 
                alt="" 
                class="absolute -bottom-2 -right-2 w-[60px] lg:w-[80px] pointer-events-none rotate-90 opacity-30"
            >
            
            <span class="font-bold text-[14px] lg:text-[20px] leading-[140%] text-black-soft relative z-10">
                <?= $item['title'] ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Section 2: Penjelasan Kurikulum -->
    <div class="flex-1 h-auto lg:h-[486px] bg-white-neutral rounded-[20px] p-[24px] relative overflow-hidden">
        <!-- Vector Kurikulum - Top Right -->
        <img 
            src="<?= url('images/vectors/vector_kurikulum.png') ?>" 
            alt="" 
            class="absolute top-0 right-0 w-[180px] pointer-events-none"
        >
        
        <div class="relative z-10">
            <h3 class="font-bold text-[20px] lg:text-[24px] leading-[38px] text-black-soft mb-[16px]">
                Penjelasan Kurikulum
            </h3>
            
            <p class="font-normal text-[14px] lg:text-[20px] leading-[24px] lg:leading-[38px] text-black-soft mb-[16px]">
                <strong>Integrasi Kurikulum Holistik Zivana memadukan Kurikulum Merdeka, Montessori Islami, dan pendekatan Neurosensory.</strong> Kami merancang pembelajaran aktif dan konkret, mendorong anak bereksplorasi untuk menumbuhkan kemandirian serta rasa ingin tahu yang tinggi.
            </p>
            
            <p class="font-normal text-[14px] lg:text-[20px] leading-[24px] lg:leading-[38px] text-black-soft">
                Personal & Berkarakter selain menanamkan <strong>Iman, Adab, dan Al-Qur'an (Metode Ummi), kami menerapkan Program Pembelajaran Individual (PPI).</strong> Pendekatan personal ini memastikan kebutuhan unik fisik, emosi, dan kognitif setiap anak terpenuhi secara optimal.
            </p>
        </div>
    </div>
</div>
