<?php
/**
 * Footer Component dengan CTA Section
 * 
 * @param bool $showCta - Apakah menampilkan CTA section dengan background merah besar (default: true)
 * @param string $ctaTitle - Judul CTA
 * @param string $ctaDescription - Deskripsi CTA
 * @param string $ctaButtonText - Text button CTA
 * @param string $ctaButtonHref - Link button CTA
 */

$showCta = $showCta ?? true;
$ctaTitle = $ctaTitle ?? 'Daftar Sekarang';
$ctaDescription = $ctaDescription ?? 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami<br>di Zivana Montessori School';
$ctaButtonText = $ctaButtonText ?? 'Daftar ke Sekolah';
$ctaButtonHref = $ctaButtonHref ?? '#';
?>

<?php if ($showCta): ?>
<!-- CTA & Footer Section with Red Background -->
<div class="mt-[-220px] pt-[220px] bg-primary rounded-tl-[500px] rounded-tr-[500px] pb-0">
    <!-- CTA Section -->
    <section class="pt-[204px]">
        <div class="container mx-auto text-center">
            <div class="relative inline-block mb-[24px]">
                <h2 class="font-bold text-[60px] leading-[100%] text-white-neutral">
                    <?= $ctaTitle ?>
                </h2>
                <!-- Vector Daftar CTA - Top Right of Title -->
                <img src="<?= url('images/vectors/vector_daftar_cta.png') ?>" alt="" class="absolute top-0 right-0 translate-x-[calc(100%-100px)] -translate-y-[calc(50%+20px)] w-[176px] h-[154px] pointer-events-none">
            </div>
            <p class="font-normal text-[24px] leading-[150%] text-white-neutral mb-[24px]">
                <?= $ctaDescription ?>
            </p>
            <div class="pb-[200px] relative inline-block">
                <!-- Vector Button CTA 1 - Top Right -->
                <img src="<?= url('images/vectors/vector_button_cta1.png') ?>" alt="" class="absolute top-0 right-0 translate-x-full -translate-y-full pointer-events-none w-[21px] h-[21px]">
                
                <!-- Vector Button CTA 2 - Bottom Left -->
                <img src="<?= url('images/vectors/vector_button_cta2.png') ?>" alt="" class="absolute bottom-0 left-0 -translate-x-full translate-y-full pointer-events-none w-[21px] h-[21px]" style="bottom: 200px;">
                
                <?php component('button', ['text' => $ctaButtonText, 'variant' => '6', 'href' => $ctaButtonHref]); ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary-dark text-white-neutral pt-[80px] relative overflow-hidden">
        <!-- Vector Footer 1 - Top Right -->
        <img src="<?= url('images/vectors/vector_footer1.png') ?>" alt="" class="absolute top-0 right-0 pointer-events-none w-[200px]">
        
        <!-- Vector Footer 2 - Bottom Left -->
        <img src="<?= url('images/vectors/vector_footer2.png') ?>" alt="" class="absolute bottom-0 left-0 pointer-events-none w-[200px]">
        
        <div class="container mx-auto px-[80px] pb-12 relative z-10">
            <!-- Footer Content - 3 Columns -->
            <div class="grid grid-cols-[auto_1fr_auto] gap-8 items-start">
                <!-- Section 1: Logo -->
                <div class="mt-[-20px]">
                    <img src="<?= url('images/logo_white.png') ?>" alt="Zivana Montessori" class="w-[330px] object-cover object-center">
                </div>
                
                <!-- Section 2: Address & Contact -->
                <div>
                    <!-- Alamat -->
                    <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                        Kunjungi kami di
                    </h4>
                    <p class="font-normal text-[20px] leading-[100%] text-white-neutral mb-[40px]">
                        Komp. Mustika Mulia Blok A4.1, Karampuang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231
                    </p>
                    
                    <!-- Kontak -->
                    <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                        Hubungi kami di
                    </h4>
                    <div class="flex items-center gap-[8px] mb-[40px]">
                        <img src="<?= url('images/vectors/vector_wa.png') ?>" alt="WhatsApp" class="w-[24px] h-[24px]">
                        <img src="<?= url('images/vectors/vector_phone.png') ?>" alt="Phone" class="w-[24px] h-[24px]">
                        <span class="font-normal text-[20px] leading-[100%] text-white-neutral">+62 0812 3456 7890</span>
                    </div>
                </div>
                
                <!-- Section 3: Social Media -->
                <div class="flex flex-col">
                    <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                        Ikuti Kami di
                    </h4>
                    <div class="flex flex-col gap-[12px] mb-[64px]">
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= url('images/vectors/vector_instagram.png') ?>" alt="Instagram" class="w-[24px] h-[24px]">
                            <span class="font-normal text-[20px] leading-[100%] text-pale-accent">Instagram</span>
                        </a>
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= url('images/vectors/vector_tiktok.png') ?>" alt="TikTok" class="w-[24px] h-[24px]">
                            <span class="font-normal text-[20px] leading-[100%] text-pale-accent">TikTok</span>
                        </a>
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= url('images/vectors/vector_facebook.png') ?>" alt="Facebook" class="w-[24px] h-[24px]">
                            <span class="font-normal text-[20px] leading-[100%] text-pale-accent">Facebook</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-left text-white-neutral">
                <p class="font-normal text-[16px] leading-[100%]">©2025 Sekolah Zivana Montessori</p>
            </div>
        </div>
    </footer>
</div>

<?php else: ?>
<!-- Footer Only (tanpa CTA dan background merah besar) -->
<footer class="bg-primary-dark text-white-neutral pt-[80px] relative overflow-hidden mt-[80px]">
    <!-- Vector Footer 1 - Top Right -->
    <img src="<?= url('images/vectors/vector_footer1.png') ?>" alt="" class="absolute top-0 right-0 pointer-events-none w-[200px]">
    
    <!-- Vector Footer 2 - Bottom Left -->
    <img src="<?= url('images/vectors/vector_footer2.png') ?>" alt="" class="absolute bottom-0 left-0 pointer-events-none w-[200px]">
    
    <div class="container mx-auto px-[80px] pb-12 relative z-10">
        <!-- Footer Content - 3 Columns -->
        <div class="grid grid-cols-[auto_1fr_auto] gap-8 items-start">
            <!-- Section 1: Logo -->
            <div class="mt-[-20px]">
                <img src="<?= url('images/logo_white.png') ?>" alt="Zivana Montessori" class="w-[330px] object-cover object-center">
            </div>
            
            <!-- Section 2: Address & Contact -->
            <div>
                <!-- Alamat -->
                <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                    Kunjungi kami di
                </h4>
                <p class="font-normal text-[20px] leading-[100%] text-white-neutral mb-[40px]">
                    Komp. Mustika Mulia Blok A4.1, Karampuang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231
                </p>
                
                <!-- Kontak -->
                <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                    Hubungi kami di
                </h4>
                <div class="flex items-center gap-[8px] mb-[40px]">
                    <img src="<?= url('images/vectors/vector_wa.png') ?>" alt="WhatsApp" class="w-[24px] h-[24px]">
                    <img src="<?= url('images/vectors/vector_phone.png') ?>" alt="Phone" class="w-[24px] h-[24px]">
                    <span class="font-normal text-[20px] leading-[100%] text-white-neutral">+62 0812 3456 7890</span>
                </div>
            </div>
            
            <!-- Section 3: Social Media -->
            <div class="flex flex-col">
                <h4 class="font-bold text-[20px] leading-[100%] text-white-neutral mb-[12px]">
                    Ikuti Kami di
                </h4>
                <div class="flex flex-col gap-[12px] mb-[64px]">
                    <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                        <img src="<?= url('images/vectors/vector_instagram.png') ?>" alt="Instagram" class="w-[24px] h-[24px]">
                        <span class="font-normal text-[20px] leading-[100%] text-pale-accent">Instagram</span>
                    </a>
                    <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                        <img src="<?= url('images/vectors/vector_tiktok.png') ?>" alt="TikTok" class="w-[24px] h-[24px]">
                        <span class="font-normal text-[20px] leading-[100%] text-pale-accent">TikTok</span>
                    </a>
                    <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                        <img src="<?= url('images/vectors/vector_facebook.png') ?>" alt="Facebook" class="w-[24px] h-[24px]">
                        <span class="font-normal text-[20px] leading-[100%] text-pale-accent">Facebook</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-left text-white-neutral">
            <p class="font-normal text-[16px] leading-[100%]">©2025 Sekolah Zivana Montessori</p>
        </div>
    </div>
</footer>
<?php endif; ?>
