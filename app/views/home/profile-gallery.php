<?php 
$pageTitle = 'Galeri Ruangan Kelas';
ob_start(); 
?>

<!-- Page Header -->
<section class="container mx-auto mt-[52px] mb-[40px]">
    <div class="bg-secondary rounded-[32px] h-[132px] p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[40px] leading-[100%] text-black-soft text-center">
            Galeri Ruangan Kelas
        </h1>
    </div>
</section>

<!-- Back Button & Gallery Content -->
<section class="container mx-auto">
    <!-- Back Button -->
    <div class="mb-[32px]">
        <?php component('button', ['text' => 'Kembali ke Profil Sekolah', 'variant' => '4', 'href' => url('/profile')]); ?>
    </div>
    
    <!-- Gallery Cards Grid -->
    <div class="grid grid-cols-3 gap-[24px]" x-data="{ showModal: false, currentImage: '', currentTitle: '' }">
        <?php 
        $galleryData = [
            ['image' => 'image_fasilitas_1.png', 'title' => 'Ruang Kelas Montessori'],
            ['image' => 'image_fasilitas_2.png', 'title' => 'Area Bermain Outdoor'],
            ['image' => 'image_fasilitas_3.png', 'title' => 'Perpustakaan Mini'],
            ['image' => 'image_fasilitas_4.png', 'title' => 'Ruang Seni & Kreativitas'],
            ['image' => 'image_fasilitas_5.png', 'title' => 'Musholla'],
        ];
        
        foreach ($galleryData as $item): 
        ?>
        <div 
            class="bg-white-neutral rounded-[20px] p-[16px] h-[264px] cursor-pointer hover:shadow-lg transition-shadow duration-200"
            @click="showModal = true; currentImage = '<?= url('images/' . $item['image']) ?>'; currentTitle = '<?= addslashes($item['title']) ?>'"
        >
            <!-- Image -->
            <div class="h-[184px] mb-[16px] rounded-[12px] overflow-hidden">
                <img 
                    src="<?= url('images/' . $item['image']) ?>" 
                    alt="<?= e($item['title']) ?>" 
                    class="w-full h-full object-cover object-center"
                >
            </div>
            
            <!-- Title -->
            <h3 class="font-normal text-[20px] leading-[100%] text-black-soft">
                <?= e($item['title']) ?>
            </h3>
        </div>
        <?php endforeach; ?>
        
        <!-- Modal -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center"
            @click.self="showModal = false"
            @keydown.escape.window="showModal = false"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/70" @click="showModal = false"></div>
            
            <!-- Modal Content -->
            <div class="relative z-10 max-w-[1116px] w-full mx-4">
                <!-- Header: Preview Foto & Close Button -->
                <div class="flex justify-between items-center mb-[12px]">
                    <h3 class="font-bold text-[20px] leading-[32px] text-white-neutral">Preview Foto</h3>
                    <button @click="showModal = false" class="text-white-neutral hover:opacity-80 transition-opacity">
                        <svg class="w-[24px] h-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Image -->
                <div class="w-full h-[626px] rounded-[12px] overflow-hidden mb-[36px]">
                    <img 
                        :src="currentImage" 
                        alt="" 
                        class="w-full h-full object-cover"
                    >
                </div>
                
                <!-- Title -->
                <p class="font-normal text-[24px] leading-[32px] text-white-neutral text-center" x-text="currentTitle"></p>
            </div>
        </div>
    </div>
    
    <!-- Tampilkan Lebih Banyak Button -->
    <div class="flex justify-center mt-[32px] mb-[88px]">
        <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '3', 'href' => '#']); ?>
    </div>
</section>

<!-- Floating Vector Galeri -->
<div class="relative">
    <img 
        src="<?= url('images/vectors/vector_galeri.png') ?>" 
        alt="" 
        class="absolute right-[50px] -top-[260px] w-[220px] h-[190px] z-10 pointer-events-none"
    >
</div>

<?php component('footer', ['showCta' => false]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
