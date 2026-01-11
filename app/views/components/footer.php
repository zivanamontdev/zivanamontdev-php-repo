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
$ctaDescription = $ctaDescription ?? 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami di Zivana Montessori School';
$ctaButtonText = $ctaButtonText ?? 'Daftar ke Sekolah';
$ctaButtonHref = $ctaButtonHref ?? url('/registration');
?>

<?php if ($showCta): ?>
<!-- CTA & Footer Section with Red Background -->
<div class="mt-[-220px] pt-[220px] bg-primary rounded-tl-[500px] rounded-tr-[500px] pb-0">
    <!-- CTA Section -->
    <section class="pt-[204px]">
        <div class="container mx-auto text-center">
            <div class="relative inline-block mb-[24px]">
                <h2 class="font-bold text-[32px] md:text-[60px] leading-[140%] text-white-neutral">
                    <?= $ctaTitle ?>
                </h2>
                <!-- Vector Daftar CTA - Top Right of Title -->
                <img src="<?= asset('images/vectors/vector_daftar_cta.png') ?>" alt="" class="absolute top-0 right-0 translate-x-[calc(100%-100px)] -translate-y-[calc(50%+20px)] w-[100px] h-[88px] md:w-[176px] md:h-[154px] pointer-events-none">
            </div>
            <p class="font-normal text-[20px] md:text-[24px] leading-[150%] text-white-neutral mb-[24px]">
                <?php
                // Pisahkan baris CTA untuk <br> hanya di desktop
                if ($ctaDescription === 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami di Zivana Montessori School') {
                    // Default CTA, split manual
                    $first = 'Yuk, daftarkan anak anda sekarang dan jadi bangun masa depan anak bersama kami';
                    $second = ' di Zivana Montessori School';
                ?>
                    <?= $first ?><span class="hidden md:inline"><br></span><?= $second ?>
                <?php } else {
                    // Custom CTA, tampilkan apa adanya
                    echo $ctaDescription;
                } ?>
            </p>
            <div class="pb-[200px] relative inline-block">
                <!-- Vector Button CTA 1 - Top Right -->
                <img src="<?= asset('images/vectors/vector_button_cta1.png') ?>" alt="" class="absolute top-0 right-0 translate-x-full -translate-y-full pointer-events-none w-[14px] h-[14px] md:w-[21px] md:h-[21px]">
                
                <!-- Vector Button CTA 2 - Bottom Left -->
                <img src="<?= asset('images/vectors/vector_button_cta2.png') ?>" alt="" class="absolute bottom-0 left-0 -translate-x-full translate-y-full pointer-events-none w-[14px] h-[14px] md:w-[21px] md:h-[21px]" style="bottom: 200px;">
                
                <?php component('button', ['text' => $ctaButtonText, 'variant' => '6', 'href' => $ctaButtonHref]); ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary-dark text-white-neutral pt-[80px] relative overflow-hidden">
        <!-- Vector Footer 1 - Top Right (hidden on mobile) -->
        <img src="<?= asset('images/vectors/vector_footer1.png') ?>" alt="" class="hidden md:block absolute top-0 right-0 pointer-events-none w-[200px]">
        
        <!-- Vector Footer 2 - Bottom Left -->
        <img src="<?= asset('images/vectors/vector_footer2.png') ?>" alt="" class="absolute bottom-0 left-0 pointer-events-none w-[200px]">
        
        <div class="container mx-auto px-5 md:px-[80px] pb-12 relative z-10">
            <!-- Footer Content - Responsive Layout -->
            <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
                <!-- Section 1: Logo -->
                <div class="mb-[20px] md:mb-0 md:mr-[4rem]">
                    <img src="<?= asset('images/logo_white.png') ?>" alt="Zivana Montessori" class="w-[158px] h-[62px] md:w-[232px] md:h-auto object-cover object-center mx-auto md:mx-0">
                </div>
                
                <!-- Section 2: Address & Contact -->
                <div class="md:flex-1">
                    <!-- Alamat -->
                    <div class="mb-[40px]">
                        <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[12px]">
                            Kunjungi kami di
                        </h4>
                        <p class="font-normal text-[14px] md:text-[20px] leading-[24px] md:leading-[140%] text-white-neutral">
                            Komp. Mustika Mulia Blok A4.1, Karampuang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231
                        </p>
                    </div>
                    
                    <!-- Kontak -->
                    <div class="mb-[24px] md:mb-[40px]">
                        <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[12px]">
                            Hubungi kami di
                        </h4>
                        <div class="flex items-center justify-center md:justify-start gap-[8px]">
                            <img src="<?= asset('images/vectors/vector_wa.png') ?>" alt="WhatsApp" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                            <img src="<?= asset('images/vectors/vector_phone.png') ?>" alt="Phone" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                            <span class="font-normal text-[14px] md:text-[20px] leading-[24px] md:leading-[140%] text-white-neutral">+62 0812 3456 7890</span>
                        </div>
                    </div>
                </div>
                
                <!-- Section 3: Social Media -->
                <div class="mb-[64px] md:ml-[2rem]">
                    <div class="flex flex-col items-center md:items-start">
                        <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[16px] md:mb-[12px]">
                            Ikuti Kami di
                        </h4>
                        <div class="flex flex-col items-center md:items-start gap-[12px]">
                            <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                                <img src="<?= asset('images/vectors/vector_instagram.png') ?>" alt="Instagram" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                                <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">Instagram</span>
                            </a>
                            <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                                <img src="<?= asset('images/vectors/vector_tiktok.png') ?>" alt="TikTok" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                                <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">TikTok</span>
                            </a>
                            <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                                <img src="<?= asset('images/vectors/vector_facebook.png') ?>" alt="Facebook" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                                <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">Facebook</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center md:text-left text-white-neutral">
                <p class="font-normal text-[12px] md:text-[16px] leading-[20px] md:leading-[140%]">©2025 Sekolah Zivana Montessori</p>
            </div>
        </div>
    </footer>
</div>

<?php else: ?>
<!-- Footer Only (tanpa CTA dan background merah besar) -->
<footer class="bg-primary-dark text-white-neutral pt-[80px] relative overflow-hidden mt-[80px]">
    <!-- Vector Footer 1 - Top Right (hidden on mobile) -->
    <img src="<?= asset('images/vectors/vector_footer1.png') ?>" alt="" class="hidden md:block absolute top-0 right-0 pointer-events-none w-[200px]">
    
    <!-- Vector Footer 2 - Bottom Left -->
    <img src="<?= asset('images/vectors/vector_footer2.png') ?>" alt="" class="absolute bottom-0 left-0 pointer-events-none w-[200px]">
    
    <div class="container mx-auto px-5 md:px-[80px] pb-12 relative z-10">
        <!-- Footer Content - Responsive Layout -->
        <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
            <!-- Section 1: Logo -->
            <div class="mb-[20px] md:mb-0 md:mr-[4rem]">
                <img src="<?= asset('images/logo_white.png') ?>" alt="Zivana Montessori" class="w-[158px] h-[62px] md:w-[232px] md:h-auto object-cover object-center mx-auto md:mx-0">
            </div>
            
            <!-- Section 2: Address & Contact -->
            <div class="md:flex-1">
                <!-- Alamat -->
                <div class="mb-[40px]">
                    <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[12px]">
                        Kunjungi kami di
                    </h4>
                    <p class="font-normal text-[14px] md:text-[20px] leading-[24px] md:leading-[140%] text-white-neutral">
                        Komp. Mustika Mulia Blok A4.1, Karampuang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231
                    </p>
                </div>
                
                <!-- Kontak -->
                <div class="mb-[24px] md:mb-[40px]">
                    <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[12px]">
                        Hubungi kami di
                    </h4>
                    <div class="flex items-center justify-center md:justify-start gap-[8px]">
                        <img src="<?= asset('images/vectors/vector_wa.png') ?>" alt="WhatsApp" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                        <img src="<?= asset('images/vectors/vector_phone.png') ?>" alt="Phone" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                        <span class="font-normal text-[14px] md:text-[20px] leading-[24px] md:leading-[140%] text-white-neutral">+62 0812 3456 7890</span>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Social Media -->
            <div class="mb-[64px] md:ml-[2rem]">
                <div class="flex flex-col items-center md:items-start">
                    <h4 class="font-bold text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-white-neutral mb-[16px] md:mb-[12px]">
                        Ikuti Kami di
                    </h4>
                    <div class="flex flex-col items-center md:items-start gap-[12px]">
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= asset('images/vectors/vector_instagram.png') ?>" alt="Instagram" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                            <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">Instagram</span>
                        </a>
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= asset('images/vectors/vector_tiktok.png') ?>" alt="TikTok" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                            <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">TikTok</span>
                        </a>
                        <a href="#" class="flex items-center gap-[8px] hover:opacity-80 transition-opacity">
                            <img src="<?= asset('images/vectors/vector_facebook.png') ?>" alt="Facebook" class="w-[16px] h-[16px] md:w-[24px] md:h-[24px]">
                            <span class="font-normal text-[16px] md:text-[20px] leading-[28px] md:leading-[140%] text-pale-accent">Facebook</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-center md:text-left text-white-neutral">
            <p class="font-normal text-[12px] md:text-[16px] leading-[20px] md:leading-[140%]">©2025 Sekolah Zivana Montessori</p>
        </div>
    </div>
</footer>
<?php endif; ?>
