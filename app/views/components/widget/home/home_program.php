<?php
/**
 * Home Program Sekolah Widget
 * 
 * Grid layout with program cards
 * 
 * @param array $highlightPrograms - Array of highlight programs from database
 */

$highlightPrograms = $highlightPrograms ?? [];

// Prepare program data from database only (no dummy data)
$program1 = $highlightPrograms[0] ?? null;
$program2 = $highlightPrograms[1] ?? null;
$program3 = $highlightPrograms[2] ?? null;

// If no programs available, don't display anything
if (!$program1 && !$program2 && !$program3) {
    echo '<div class="text-center py-8 text-white-soft"><p>Belum ada program highlight yang ditampilkan</p></div>';
    return;
}

// Get image URLs - check if image starts with / (absolute path from public) or is just filename
$imageUrl1 = !empty($program1['image']) ? 
    (strpos($program1['image'], '/') === 0 ? url($program1['image']) : url('/uploads/programs-tahun/' . $program1['image'])) : 
    'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22524%22%3E%3Crect width=%22800%22 height=%22524%22 fill=%22%23E0E0E0%22/%3E%3C/svg%3E';
$imageUrl2 = !empty($program2['image']) ? 
    (strpos($program2['image'], '/') === 0 ? url($program2['image']) : url('/uploads/programs-tahun/' . $program2['image'])) : 
    'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22250%22%3E%3Crect width=%22800%22 height=%22250%22 fill=%22%23E0E0E0%22/%3E%3C/svg%3E';
$imageUrl3 = !empty($program3['image']) ? 
    (strpos($program3['image'], '/') === 0 ? url($program3['image']) : url('/uploads/programs-tahun/' . $program3['image'])) : 
    'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22250%22%3E%3Crect width=%22400%22 height=%22250%22 fill=%22%23E0E0E0%22/%3E%3C/svg%3E';
?>

<div class="flex flex-col lg:flex-row gap-[24px] h-auto lg:h-[524px]">
    <!-- Left Card - Program 1 -->
    <div class="w-full lg:flex-1 relative rounded-[24px] md:rounded-[32px] overflow-hidden h-[524px] lg:h-auto">
        <!-- Background Image -->
        <img 
            src="<?= $imageUrl1 ?>" 
            alt="<?= e($program1['name']) ?>" 
            class="absolute inset-0 w-full h-full object-cover"
            onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22524%22%3E%3Crect width=%22800%22 height=%22524%22 fill=%22%23E0E0E0%22/%3E%3Cg transform=%22translate(400,262)%22%3E%3Crect x=%22-40%22 y=%22-35%22 width=%2280%22 height=%2260%22 rx=%224%22 fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22/%3E%3Ccircle cx=%22-20%22 cy=%22-15%22 r=%228%22 fill=%22%23999%22/%3E%3Cpath d=%22M-40,5 L-15,-15 L10,5 L40,15 L40,25 L-40,25 Z%22 fill=%22%23999%22/%3E%3C/g%3E%3C/svg%3E';"
        >
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
        
        <!-- Content -->
        <div class="relative h-full flex flex-col justify-end p-[16px] md:p-[20px]">
            <h3 class="font-bold text-[20px] md:text-[24px] leading-[140%] md:leading-[32px] tracking-[0%] text-white-neutral mb-[12px] md:mb-[16px]">
                <?= e($program1['name']) ?>
            </h3>
            <p class="font-normal text-[20px] leading-[32px] tracking-[0%] text-white-neutral">
                <?= e($program1['description']) ?>
            </p>
        </div>
    </div>
    
    <!-- Right Section -->
    <div class="w-full lg:flex-1 flex flex-col gap-[24px]">
        <!-- Top Card - Program 2 -->
        <div class="relative rounded-[20px] overflow-hidden h-[262px]">
            <!-- Vector Program 2 (floating on right side) -->
            <img 
                src="<?= url('/images/vectors/vector_program2.png') ?>" 
                alt="" 
                class="absolute top-1/2 -right-[0px] -translate-y-1/2 w-[80px] h-[80px] pointer-events-none z-20"
            >
            
            <!-- Background Image -->
            <img 
                src="<?= $imageUrl2 ?>" 
                alt="<?= e($program2['name']) ?>" 
                class="absolute inset-0 w-full h-full object-cover"
                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22250%22%3E%3Crect width=%22800%22 height=%22250%22 fill=%22%23E0E0E0%22/%3E%3Cg transform=%22translate(400,125)%22%3E%3Crect x=%22-40%22 y=%22-35%22 width=%2280%22 height=%2260%22 rx=%224%22 fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22/%3E%3Ccircle cx=%22-20%22 cy=%22-15%22 r=%228%22 fill=%22%23999%22/%3E%3Cpath d=%22M-40,5 L-15,-15 L10,5 L40,15 L40,25 L-40,25 Z%22 fill=%22%23999%22/%3E%3C/g%3E%3C/svg%3E';"
            >
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            
            <!-- Content -->
            <div class="relative h-full flex flex-col justify-end p-[16px] md:p-[20px] z-30">
                <h3 class="font-bold text-[20px] md:text-[24px] leading-[140%] md:leading-[32px] tracking-[0%] text-white-neutral mb-[12px] md:mb-[16px]">
                    <?= e($program2['name']) ?>
                </h3>
                <p class="font-normal text-[16px] md:text-[20px] leading-[140%] md:leading-[32px] tracking-[0%] text-white-neutral">
                    <?= e($program2['description']) ?>
                </p>
            </div>
        </div>
        
        <!-- Bottom Section - 2 columns -->
        <div class="flex-1 flex flex-col md:flex-row gap-[24px]">
            <!-- Left - Program 3 -->
            <div class="md:flex-1 relative rounded-[20px] overflow-hidden h-[174px] md:h-auto">
                <!-- Background Image -->
                <img 
                    src="<?= $imageUrl3 ?>" 
                    alt="<?= e($program3['name']) ?>" 
                    class="absolute inset-0 w-full h-full object-cover"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22250%22%3E%3Crect width=%22400%22 height=%22250%22 fill=%22%23E0E0E0%22/%3E%3Cg transform=%22translate(200,125)%22%3E%3Crect x=%22-40%22 y=%22-35%22 width=%2280%22 height=%2260%22 rx=%224%22 fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22/%3E%3Ccircle cx=%22-20%22 cy=%22-15%22 r=%228%22 fill=%22%23999%22/%3E%3Cpath d=%22M-40,5 L-15,-15 L10,5 L40,15 L40,25 L-40,25 Z%22 fill=%22%23999%22/%3E%3C/g%3E%3C/svg%3E';"
                >
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                
                <!-- Content -->
                <div class="relative h-full flex flex-col justify-end p-[16px] md:p-[20px]">
                    <h3 class="font-bold text-[20px] md:text-[24px] leading-[140%] md:leading-[32px] tracking-[0%] text-white-neutral">
                        <?= e($program3['name']) ?>
                    </h3>
                </div>
            </div>
            
            <!-- Right - Text & Button -->
            <div class="w-full md:flex-1 flex flex-col justify-center items-center">
                <p class="font-bold text-[20px] md:text-[28px] leading-[140%] tracking-[0%] text-black-soft mb-[20px] md:mb-[26px] text-center">
                    Serta banyak program sekolah bermanfaat lainnya!
                </p>
                <?php component('button', [
                    'text' => 'Lihat program sekolah',
                    'variant' => '1',
                    'href' => '#'
                ]); ?>
            </div>
        </div>
    </div>
</div>
