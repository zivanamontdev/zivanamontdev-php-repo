<?php
/**
 * Home Testimoni Widget
 * 
 * Testimonial section with 3 cards layout
 * 
 * @param array $testimonials - Array of testimonials from database
 */

$testimonials = $testimonials ?? [];

if (empty($testimonials)) {
    ?>
    <div class="bg-white-neutral rounded-[24px] p-[24px] text-center">
        <p class="font-normal text-[16px] leading-[28px] text-black-soft">Belum ada testimoni yang ditampilkan.</p>
    </div>
    <?php
    return;
}

$testimonials = array_values(array_slice($testimonials, 0, 3));
$testi1 = $testimonials[0];
$testi2 = $testimonials[1] ?? $testimonials[0];
$testi3 = $testimonials[2] ?? ($testimonials[1] ?? $testimonials[0]);

$homeTestimoniImageUrl = function ($image) {
    if (empty($image)) {
        return asset('images/image_testi.jpg');
    }

    if (preg_match('/^https?:\/\//', $image)) {
        return $image;
    }

    if (strpos($image, 'uploads/') === 0) {
        return asset($image);
    }

    return asset('uploads/testimonials/' . ltrim($image, '/'));
};

$testimonialCards = [
    [
        'data' => $testi1,
        'imageUrl' => $homeTestimoniImageUrl($testi1['image'] ?? null),
        'outerClass' => 'w-full lg:flex-1 h-[560px] bg-white-neutral rounded-[24px] p-[20px] md:p-[24px] relative flex flex-col overflow-hidden',
        'bubbleClass' => 'bg-white-secondary',
        'textClass' => 'font-normal text-[16px] md:text-[20px] leading-[160%] text-black-neutral text-justify testimoni-line-clamp-8',
        'buttonClass' => 'text-primary',
        'star' => [
            'src' => asset('images/vectors/vector_star1.png'),
            'class' => 'absolute top-0 -right-[50px] w-[230px] h-[230px] pointer-events-none z-0'
        ]
    ],
    [
        'data' => $testi2,
        'imageUrl' => $homeTestimoniImageUrl($testi2['image'] ?? null),
        'outerClass' => 'h-[268px] bg-secondary rounded-[24px] p-[24px] relative overflow-hidden flex flex-col',
        'bubbleClass' => 'bg-[#F5B746]',
        'textClass' => 'font-normal text-[16px] leading-[170%] text-black-soft text-justify testimoni-line-clamp-3',
        'buttonClass' => 'text-black-soft',
        'star' => [
            'src' => asset('images/vectors/vector_star2.png'),
            'class' => 'absolute top-0 right-0 w-[150px] h-[150px] pointer-events-none z-0'
        ]
    ],
    [
        'data' => $testi3,
        'imageUrl' => $homeTestimoniImageUrl($testi3['image'] ?? null),
        'outerClass' => 'h-[268px] bg-white-neutral rounded-[24px] p-[24px] relative overflow-hidden flex flex-col',
        'bubbleClass' => 'bg-white-secondary',
        'textClass' => 'font-normal text-[16px] leading-[170%] text-black-soft text-justify testimoni-line-clamp-3',
        'buttonClass' => 'text-primary',
        'star' => [
            'src' => asset('images/vectors/vector_star3.png'),
            'class' => 'absolute bottom-0 right-0 w-[150px] h-[150px] pointer-events-none z-0'
        ]
    ],
];
?>

<style>
    .testimoni-title-clamp-2,
    .testimoni-line-clamp-3,
    .testimoni-line-clamp-8 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .testimoni-title-clamp-2 {
        -webkit-line-clamp: 2;
        line-clamp: 2;
    }

    .testimoni-line-clamp-3 {
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    .testimoni-line-clamp-8 {
        -webkit-line-clamp: 8;
        line-clamp: 8;
    }

    .testimoni-modal-open {
        overflow: hidden;
    }
</style>

<div class="flex flex-col lg:flex-row gap-[24px]" id="home-testimoni-section">
    <!-- Card 1 - Left (Full height) -->
    <div class="<?= e($testimonialCards[0]['outerClass']) ?>">
        <!-- Vector Star -->
        <img
            src="<?= $testimonialCards[0]['star']['src'] ?>"
            alt=""
            class="<?= e($testimonialCards[0]['star']['class']) ?>"
        >
        
        <!-- Title -->
        <div class="h-[90px] mb-[24px] relative z-10 overflow-hidden">
            <h3 class="font-bold text-[24px] md:text-[32px] leading-[140%] text-black-soft testimoni-title-clamp-2">
                <?= !empty($testi1['highlight_text']) ? e($testi1['highlight_text']) : e($testi1['testimonial_text']) ?>
            </h3>
        </div>
        
        <!-- Testimonial Text -->
        <div class="<?= e($testimonialCards[0]['bubbleClass']) ?> rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[16px] md:mb-[24px] relative z-10 flex-1 min-h-0">
            <p class="<?= e($testimonialCards[0]['textClass']) ?>">
                <?= e($testi1['testimonial_text']) ?>
            </p>
            <button
                type="button"
                class="testimoni-read-more mt-[8px] font-bold text-[14px] leading-[20px] <?= e($testimonialCards[0]['buttonClass']) ?> hover:underline"
                data-testimoni='<?= e(json_encode($testimonialCards[0]['data'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) ?>'
                data-image="<?= e($testimonialCards[0]['imageUrl']) ?>"
            >
                Lihat Selengkapnya
            </button>
        </div>
        
        <!-- Profile -->
        <div class="flex items-center mt-auto relative z-10">
            <img
                src="<?= $testimonialCards[0]['imageUrl'] ?>"
                alt="<?= e($testi1['parent_name']) ?>"
                class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22%3E%3Ccircle cx=%2222%22 cy=%2222%22 r=%2222%22 fill=%22%23E0E0E0%22/%3E%3Cpath d=%22M22,10 a5,5 0 1,0 0,10 a5,5 0 1,0 0,-10 M22,25 a10,8 0 0,0 -10,8 h20 a10,8 0 0,0 -10,-8%22 fill=%22%23999%22/%3E%3C/svg%3E';"
            >
            <div>
                <p class="font-bold text-[16px] text-black-neutral mb-[4px]"><?= e($testi1['parent_name']) ?></p>
                <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari <?= e($testi1['child_name']) ?></p>
            </div>
        </div>
    </div>
    
    <!-- Right Section - 2 cards stacked vertically -->
    <div class="w-full lg:flex-1 h-[560px] flex flex-col gap-[24px]">
        <?php foreach ([$testimonialCards[1], $testimonialCards[2]] as $card): ?>
        <div class="<?= e($card['outerClass']) ?>">
            <img
                src="<?= $card['star']['src'] ?>"
                alt=""
                class="<?= e($card['star']['class']) ?>"
            >
            
            <!-- Testimonial Text -->
            <div class="<?= e($card['bubbleClass']) ?> rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[12px] mb-[16px] relative z-10 flex-shrink-0">
                <p class="<?= e($card['textClass']) ?>">
                    <?= e($card['data']['testimonial_text']) ?>
                </p>
                <button
                    type="button"
                    class="testimoni-read-more mt-[6px] font-bold text-[14px] leading-[20px] <?= e($card['buttonClass']) ?> hover:underline"
                    data-testimoni='<?= e(json_encode($card['data'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) ?>'
                    data-image="<?= e($card['imageUrl']) ?>"
                >
                    Lihat Selengkapnya
                </button>
            </div>
            
            <!-- Profile -->
            <div class="flex items-center mt-auto relative z-10">
                <img
                    src="<?= $card['imageUrl'] ?>"
                    alt="<?= e($card['data']['parent_name']) ?>"
                    class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22%3E%3Ccircle cx=%2222%22 cy=%2222%22 r=%2222%22 fill=%22%23E0E0E0%22/%3E%3Cpath d=%22M22,10 a5,5 0 1,0 0,10 a5,5 0 1,0 0,-10 M22,25 a10,8 0 0,0 -10,8 h20 a10,8 0 0,0 -10,-8%22 fill=%22%23999%22/%3E%3C/svg%3E';"
                >
                <div>
                    <p class="font-bold text-[16px] text-black-neutral mb-[4px]"><?= e($card['data']['parent_name']) ?></p>
                    <p class="font-normal text-[16px] text-black-neutral">Orang Tua dari <?= e($card['data']['child_name']) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Backdrop -->
<div id="modal-testimoni-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-5">
    <!-- Modal Container -->
    <div class="bg-white-neutral border border-border-soft rounded-[16px] w-full max-w-[621px] px-[24px] py-[20px] max-h-[85vh] overflow-y-auto">
        <!-- Header: Title and Close Button -->
        <div class="flex items-start justify-between mb-[32px]">
            <h3 class="font-bold text-[20px] leading-[140%] text-black-soft">Testimoni Orang Tua Siswa</h3>
            <button type="button" id="close-modal-testimoni-detail" class="text-black-highlight hover:text-black-soft transition-colors ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Description -->
        <div class="bg-white-secondary rounded-tl-[24px] rounded-tr-[24px] rounded-br-[24px] rounded-bl-[8px] p-[16px] mb-[32px]">
            <p id="modal-testimoni-text" class="font-normal text-[16px] leading-[28px] text-black-soft text-justify whitespace-pre-line"></p>
        </div>
        
        <!-- Profile -->
        <div class="flex items-center">
            <img
                id="modal-testimoni-image"
                src="<?= asset('images/image_testi.jpg') ?>"
                alt=""
                class="w-[44px] h-[44px] rounded-full object-cover mr-[20px]"
            >
            <div>
                <p id="modal-testimoni-parent" class="font-bold text-[16px] text-black-neutral mb-[4px]"></p>
                <p id="modal-testimoni-child" class="font-normal text-[16px] text-black-neutral"></p>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('modal-testimoni-detail');
    const closeBtn = document.getElementById('close-modal-testimoni-detail');
    const textEl = document.getElementById('modal-testimoni-text');
    const parentEl = document.getElementById('modal-testimoni-parent');
    const childEl = document.getElementById('modal-testimoni-child');
    const imageEl = document.getElementById('modal-testimoni-image');
    const fallbackImage = '<?= asset('images/image_testi.jpg') ?>';

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('testimoni-modal-open');
    }

    document.querySelectorAll('.testimoni-read-more').forEach(function(button) {
        button.addEventListener('click', function() {
            const data = JSON.parse(button.dataset.testimoni || '{}');

            textEl.textContent = data.testimonial_text || '';
            parentEl.textContent = data.parent_name || '';
            childEl.textContent = data.child_name ? 'Orang Tua dari ' + data.child_name : '';
            imageEl.src = button.dataset.image || fallbackImage;
            imageEl.alt = data.parent_name || 'Testimoni';
            imageEl.onerror = function() {
                imageEl.onerror = null;
                imageEl.src = fallbackImage;
            };

            modal.classList.remove('hidden');
            document.body.classList.add('testimoni-modal-open');
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
})();
</script>
