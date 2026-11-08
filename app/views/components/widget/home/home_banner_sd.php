<?php
/**
 * Home Banner SD Widget Component
 *
 * Banner pendaftaran Zivana Montessori School - Primary di beranda TK.
 * Desain: Figma "Zivana Montessori - Landing Page Design", halaman "UI Design TK",
 * frame "Landing Page · Banner SD" (node 2114:517) dan versi mobile (node 2114:795).
 *
 * Ubah $primaryUrl kalau alamat situs SD berubah.
 */

$primaryUrl = 'https://primary.sekolahzivanamontessori.sch.id/id/';
?>

<div class="w-full lg:container mx-auto px-5">
    <div class="bg-pale-accent rounded-[24px] md:rounded-[32px] px-5 py-6 md:px-14 md:py-12 flex flex-col md:flex-row md:items-center gap-4 md:gap-10">
        <!-- Ilustrasi: di HP tampil di atas, di desktop di kanan -->
        <img
            src="<?= asset('images/banner-sd-illustration.webp') ?>"
            alt=""
            width="180"
            height="180"
            loading="lazy"
            class="w-24 h-24 md:w-[180px] md:h-[180px] object-contain md:order-last md:shrink-0"
        >

        <div class="flex flex-col items-start gap-4 md:flex-1">
            <span class="inline-block bg-white-neutral text-black-soft rounded-[32px] py-2 px-[14px] md:px-5 text-[12px] leading-[20px] md:text-[16px] md:leading-[28px]">
                Zivana Montessori School - Primary
            </span>

            <h2 class="text-black-soft font-bold text-[24px] leading-[38px] md:text-[32px] md:leading-[52px]">
                Pendaftaran SD Zivana Montessori Sudah Dibuka
            </h2>

            <p class="text-black-highlight font-normal text-[16px] leading-[28px] md:text-[20px] md:leading-[32px] max-w-[788px]">
                Lanjutkan perjalanan Montessori si kecil ke jenjang sekolah dasar. Kami membuka satu kelas 1 untuk tahun ajaran 2027/2028 dengan kuota terbatas.
            </p>

            <?php component('button', [
                'text' => 'Kunjungi Website SD',
                'variant' => '1',
                'href' => $primaryUrl,
            ]); ?>
        </div>
    </div>
</div>
