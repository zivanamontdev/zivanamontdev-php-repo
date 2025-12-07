<?php 
$pageTitle = 'Pendaftaran Sekolah';
ob_start(); 
?>

<!-- Hero Section -->
<section class="container mx-auto mt-[52px] mb-[80px]">
    <div class="bg-primary rounded-[24px] p-[24px] h-[214px] relative overflow-hidden flex items-center">
        <!-- Vector Star 4 -->
        <img 
            src="<?= url('/images/vectors/vector_star4.png') ?>" 
            alt="" 
            class="absolute bottom-0 -left-[30px] w-[150px] h-[150px] pointer-events-none z-0"
        >
        
        <!-- Title -->
        <h1 class="font-bold text-[32px] leading-[38px] text-white-neutral relative z-10">
            Pendaftaran Sekolah Zivana Montessori
        </h1>
    </div>
</section>

<!-- Form Section -->
<section class="container mx-auto mb-[88px] relative">
    <!-- Vector Registration - Outside container to reach screen edge -->
    <img 
        src="<?= url('images/vectors/vector_registration.png') ?>" 
        alt="" 
        class="absolute top-[122px] -left-[calc((100vw-100%)/2)] w-[650px] h-[600px] pointer-events-none z-0"
    >
    
    <div class="grid grid-cols-2 gap-[24px]">
        <!-- Section 1: Information Card -->
        <div class="bg-white-neutral rounded-[20px] p-[24px] h-[272px] flex flex-col relative z-10">
            <!-- Title -->
            <h2 class="font-bold text-[24px] leading-[35px] text-black-soft mb-[16px]">
                Informasi Pendaftaran
            </h2>
            
            <!-- Description -->
            <p class="font-normal text-[16px] leading-[150%] text-black-soft mb-auto">
                Setelah mengisi form, anda akan diarahkan ke halaman WhatsApp untuk mengirim pesan kepada admin sesuai dengan data yang anda berikan.
            </p>
            
            <!-- Shield Icon & Privacy Text -->
            <div class="flex flex-col gap-[16px]">
                <svg class="w-[20px] h-[24px] flex-shrink-0" fill="none" stroke="#0F65FA" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <p class="font-normal text-[16px] leading-[100%] text-black-highlight">
                    Informasi yang anda berikan bersifat privasi dan akan dijaga kerahasiaannya.
                </p>
            </div>
        </div>
        
        <!-- Section 2: Registration Form -->
        <div>
            <form action="<?= url('/registration') ?>" method="POST">
                <?= csrf_field() ?>
                
                <!-- Nama Orang Tua -->
                <?php component('input', [
                    'name' => 'parent_name',
                    'label' => 'Nama Orang Tua',
                    'placeholder' => 'Isi nama orang tua anak',
                    'value' => old('parent_name'),
                    'required' => true
                ]); ?>
                
                <!-- Nama Anak -->
                <?php component('input', [
                    'name' => 'child_name',
                    'label' => 'Nama Anak',
                    'placeholder' => 'Isi nama anak',
                    'value' => old('child_name'),
                    'required' => true,
                    'class' => 'mt-[20px]'
                ]); ?>
                
                <!-- Usia Anak -->
                <?php component('input', [
                    'name' => 'child_age',
                    'label' => 'Usia Anak',
                    'placeholder' => 'Isi usia anak (contoh: 2 tahun)',
                    'value' => old('child_age'),
                    'required' => true,
                    'class' => 'mt-[20px]'
                ]); ?>
                
                <!-- Alamat -->
                <?php component('input', [
                    'name' => 'address',
                    'label' => 'Alamat',
                    'placeholder' => 'Isi alamat saat ini',
                    'value' => old('address'),
                    'required' => true,
                    'class' => 'mt-[20px]'
                ]); ?>
                
                <!-- Nomor WhatsApp -->
                <?php component('input', [
                    'name' => 'whatsapp',
                    'type' => 'tel',
                    'label' => 'Nomor WhatsApp',
                    'placeholder' => 'Isi nomor whatsapp (contoh: 085123456789)',
                    'value' => old('whatsapp'),
                    'required' => true,
                    'class' => 'mt-[20px]'
                ]); ?>
                
                <!-- Submit Button -->
                <div class="mt-[20px]">
                    <?php component('button', [
                        'text' => 'Daftar Sekarang',
                        'variant' => '1',
                        'type' => 'submit',
                        'class' => 'w-full'
                    ]); ?>
                </div>
            </form>
        </div>
    </div>
</section>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
