<?php 
$pageTitle = 'School Profile';
ob_start(); 
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center">School Profile</h1>
        <p class="text-center text-purple-100 mt-4 text-lg">Learn more about our mission, vision, and dedicated team</p>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <div class="bg-purple-50 rounded-xl p-8">
                <h2 class="text-3xl font-bold mb-4 text-purple-600">Our Vision</h2>
                <p class="text-gray-700 text-lg">
                    <?= e($settings['school_description'] ?: 'To be a leading Montessori institution that nurtures independent, confident, and compassionate learners who contribute positively to society.') ?>
                </p>
            </div>
            
            <div class="bg-indigo-50 rounded-xl p-8">
                <h2 class="text-3xl font-bold mb-4 text-indigo-600">Our Mission</h2>
                <p class="text-gray-700 text-lg">
                    To provide an authentic Montessori education that respects each child's unique learning journey, fostering independence, creativity, and a lifelong love of learning.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Staff Members Section -->
<?php if (!empty($employees)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center">Our Team</h2>
        
        <?php foreach ($employees as $levelKey => $levelData): ?>
            <?php if (!empty($levelData['employees'])): ?>
                <div class="mb-12">
                    <h3 class="text-2xl font-bold mb-6 text-purple-600"><?= e($levelData['label']) ?></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <?php foreach ($levelData['employees'] as $employee): ?>
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                                <?php if ($employee['photo']): ?>
                                    <img src="<?= upload($employee['photo']) ?>" alt="<?= e($employee['full_name']) ?>" class="w-full h-48 object-cover">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold"><?= strtoupper(substr($employee['full_name'], 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-4">
                                    <h4 class="font-bold text-lg mb-1"><?= e($employee['full_name']) ?></h4>
                                    <p class="text-purple-600 text-sm mb-2"><?= e($employee['position']) ?></p>
                                    <?php if ($employee['bio']): ?>
                                        <p class="text-gray-600 text-sm"><?= e(str_limit($employee['bio'], 100)) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Awards Section -->
<?php if (!empty($awards)): ?>
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Our Achievements</h2>
        
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($awards as $award): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                        <img src="<?= upload($award['image']) ?>" alt="<?= e($award['title']) ?>" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="text-purple-600 font-bold mb-2"><?= e($award['year_received']) ?></div>
                            <h4 class="font-bold text-lg mb-2"><?= e($award['title']) ?></h4>
                            <?php if ($award['description']): ?>
                                <p class="text-gray-600 text-sm"><?= e($award['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
