<?php 
$pageTitle = 'Articles';
ob_start(); 
?>

<!-- Page Header -->
<section class="container mx-auto mt-[52px] mb-[80px]">
    <div class="bg-primary rounded-[32px] h-[132px] p-[40px] relative overflow-hidden flex items-center justify-center">
        <!-- Background mask with gradient opacity -->
        <div class="absolute inset-0 pointer-events-none" style="mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.12) 60%, rgba(0,0,0,0.25) 100%);">
            <img 
                src="<?= url('/images/mask_group.png') ?>" 
                alt="" 
                class="w-full h-full object-cover"
            >
        </div>
        
        <!-- Title -->
        <h1 class="relative z-10 font-normal text-[40px] leading-[100%] text-white-neutral text-center">
            Artikel dan Berita Terkini
        </h1>
    </div>
</section>

<!-- Section Grid Berita -->
<?php component('widget/articles/articles_grid_section'); ?>

<!-- Section List Card Berita -->
<?php component('widget/articles/articles_list_card_section'); ?>

<!-- Load More Button -->
<div class="container mx-auto mt-[32px] flex justify-end">
    <?php component('button', ['text' => 'Tampilkan Lebih Banyak', 'variant' => '5', 'href' => '#']); ?>
</div>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
