<?php
/**
 * Activities Kurikulum Widget Component
 * 
 * Displays curriculum information with cards and description
 */

$kurikulumItems = [
    [
        'image' => 'activity_tutwuri_handayani.png',
        'width' => 54,
        'height' => 56,
        'title' => 'Kurikulum Nasional'
    ],
    [
        'image' => 'activity_bulan_bintang.png',
        'width' => 53,
        'height' => 56,
        'title' => 'Kurikulum Iman dan Adab'
    ],
    [
        'image' => 'activity_ummi.png',
        'width' => 56,
        'height' => 56,
        'title' => 'Metode Ummi'
    ],
    [
        'image' => 'activity_logo.png',
        'width' => 56,
        'height' => 56,
        'title' => 'Kurikulum Zivana Montessori (Montessori Islamic)'
    ]
];
?>

<div class="flex gap-[24px]">
    <!-- Section 1: Kurikulum Cards -->
    <div class="flex-1 flex flex-col gap-[20px]">
        <?php foreach ($kurikulumItems as $item): ?>
        <div class="h-[107px] py-[15px] px-[24px] rounded-[20px] bg-white-neutral flex items-center">
            <img 
                src="<?= url('images/' . $item['image']) ?>" 
                alt="<?= $item['title'] ?>" 
                class="object-contain mr-[16px]"
                style="width: <?= $item['width'] ?>px; height: <?= $item['height'] ?>px;"
            >
            <span class="font-bold text-[20px] leading-[100%] text-black-soft">
                <?= $item['title'] ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Section 2: Penjelasan Kurikulum -->
    <div class="flex-1 h-[486px] bg-white-neutral rounded-[20px] p-[24px] relative overflow-hidden">
        <!-- Vector Kurikulum - Top Right -->
        <img 
            src="<?= url('images/vectors/vector_kurikulum.png') ?>" 
            alt="" 
            class="absolute top-0 right-0 w-[180px] pointer-events-none"
        >
        
        <div class="relative z-10">
            <h3 class="font-bold text-[24px] leading-[38px] text-black-soft mb-[16px]">
                Penjelasan Kurikulum
            </h3>
            
            <p class="font-normal text-[20px] leading-[38px] text-black-soft mb-[16px]">
                Sekolah Zivana Montessori mengintegrasikan Kurikulum Nasional sebagai dasar pembelajaran, diperkaya dengan Kurikulum Iman dan Adab untuk membentuk karakter dan akhlak sejak dini. Proses literasi Al-Qur'an menggunakan Metode Ummi yang sistematis dan mudah dipahami anak.
            </p>
            
            <p class="font-normal text-[20px] leading-[38px] text-black-soft">
                Semua itu dipadukan dengan Kurikulum Zivana Montessori (Montessori Islamic) yang menekankan kemandirian, eksplorasi, dan pembelajaran holistik sesuai prinsip Montessori. Kombinasi ini memastikan anak tumbuh cerdas, berkarakter, dan percaya diri.
            </p>
        </div>
    </div>
</div>
