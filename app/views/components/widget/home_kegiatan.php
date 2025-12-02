<?php
/**
 * Home Kegiatan Widget
 * 
 * Activity/Event card with date and details
 * 
 * @param string $tanggal - Day of month (e.g., "20")
 * @param string $bulan - Month abbreviation (e.g., "NOV")
 * @param string $tahun - Year (e.g., "2025")
 * @param string $nama_kegiatan - Activity name
 * @param string $jam - Time range (e.g., "08:00 - 12:00")
 * @param string $tempat - Location
 * @param string $status - Event status ('public' or 'private')
 */

$tanggal = $tanggal ?? '20';
$bulan = $bulan ?? 'NOV';
$tahun = $tahun ?? '2025';
$nama_kegiatan = $nama_kegiatan ?? 'Nama Kegiatan';
$jam = $jam ?? '08:00 - 12:00';
$tempat = $tempat ?? 'Tempat Kegiatan';
$status = $status ?? 'private';
?>

<div class="grid grid-cols-12 gap-[24px]">
    <!-- Section 1 - Date -->
    <div class="col-span-2 h-[214px] bg-primary rounded-[24px] p-[24px] relative overflow-hidden flex items-center justify-center">
        <!-- Vector Star 4 -->
        <img 
            src="<?= url('/images/vectors/vector_star4.png') ?>" 
            alt="" 
            class="absolute bottom-0 -left-[30px] w-[150px] h-[150px] pointer-events-none z-0"
        >
        
        <!-- Date Content -->
        <div class="text-center relative z-10">
            <p class="text-white-neutral">
                <span class="font-bold text-[32px] leading-[38px]"><?= e($tanggal) ?></span>
                <span class="font-normal text-[32px] leading-[38px]"> <?= e($bulan) ?></span>
            </p>
            <p class="font-normal text-[20px] leading-[32px] text-white-neutral"><?= e($tahun) ?></p>
        </div>
    </div>
    
    <!-- Section 2 - Details -->
    <div class="col-span-10 bg-white-neutral rounded-[24px] p-[24px]">
        <!-- Nama Kegiatan & Status -->
        <div class="flex items-center justify-between mb-[16px]">
            <h3 class="font-bold text-[24px] leading-[38px] text-black-soft">
                <?= e($nama_kegiatan) ?>
            </h3>
            <?php if ($status === 'public'): ?>
                <span class="bg-primary px-[8px] rounded-[4px] font-normal text-[14px] leading-[24px] text-white-neutral">
                    Terbuka untuk Umum
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Jam & Tempat -->
        <div class="flex mb-[16px]">
            <!-- Jam -->
            <div class="flex-1">
                <p class="font-normal text-[20px] leading-[32px] text-black-soft">Jam</p>
                <p class="font-bold text-[20px] leading-[32px] text-black-soft"><?= e($jam) ?></p>
            </div>
            
            <!-- Tempat -->
            <div class="flex-1">
                <p class="font-normal text-[20px] leading-[32px] text-black-soft">Tempat</p>
                <p class="font-bold text-[20px] leading-[32px] text-black-soft"><?= e($tempat) ?></p>
            </div>
        </div>
        
        <!-- Button Text -->
        <a href="#" class="font-normal text-[20px] leading-[32px] text-primary hover:underline">
            Lihat Informasi Kegiatan
        </a>
    </div>
</div>
