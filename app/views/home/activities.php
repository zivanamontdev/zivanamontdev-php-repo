<?php 
$pageTitle = 'School Activities';
ob_start(); 
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center">Our School Activities</h1>
        <p class="text-center text-purple-100 mt-4 text-lg">Explore our comprehensive learning programs and daily schedule</p>
    </div>
</section>

<!-- Learning Programs Section -->
<?php if (!empty($programs)): ?>
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Learning Programs</h2>
        
        <?php foreach ($programs as $program): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h3 class="text-2xl font-bold text-purple-600 mb-4"><?= e($program['name']) ?></h3>
                <div class="prose max-w-none mb-6 text-gray-700">
                    <?= $program['description'] ?>
                </div>
                
                <?php if (!empty($program['images'])): ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <?php foreach ($program['images'] as $image): ?>
                            <div class="relative group cursor-pointer overflow-hidden rounded-lg">
                                <img src="<?= upload($image['filename']) ?>" 
                                     alt="<?= e($image['caption'] ?: $program['name']) ?>" 
                                     class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                                <?php if ($image['caption']): ?>
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white p-2 text-sm">
                                        <?= e($image['caption']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- School Schedule Section -->
<?php if (!empty($schedules)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Daily School Schedule</h2>
        
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="divide-y">
                <?php foreach ($schedules as $schedule): ?>
                    <div class="p-6 hover:bg-purple-50 transition">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-32">
                                <span class="text-purple-600 font-bold text-lg">
                                    <?= date('H:i', strtotime($schedule['time_start'])) ?>
                                    <?php if ($schedule['time_end']): ?>
                                        - <?= date('H:i', strtotime($schedule['time_end'])) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="ml-6">
                                <h4 class="font-bold text-lg mb-1"><?= e($schedule['activity_name']) ?></h4>
                                <?php if ($schedule['description']): ?>
                                    <p class="text-gray-600"><?= e($schedule['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
