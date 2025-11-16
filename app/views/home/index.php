<?php 
$pageTitle = 'Home';
ob_start(); 
?>

<!-- Hero Section -->
<section class="hero-gradient text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center animate-fade-in">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Welcome to Zivana Montessori School</h1>
            <p class="text-xl md:text-2xl mb-8 text-purple-100">Where every child's potential blossoms through authentic Montessori education</p>
            <a href="<?= url('/registration') ?>" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition">Enroll Your Child Today</a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Why Choose Zivana Montessori?</h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">We provide an authentic Montessori learning experience that nurtures your child's natural curiosity and love for learning</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Authentic Montessori Method</h3>
                <p class="text-gray-600">We strictly follow Dr. Maria Montessori's proven educational philosophy, allowing children to learn at their own pace in a prepared environment.</p>
            </div>
            
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Certified Teachers</h3>
                <p class="text-gray-600">Our team consists of Montessori-certified educators with years of experience in early childhood development and education.</p>
            </div>
            
            <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Nurturing Environment</h3>
                <p class="text-gray-600">We create a warm, safe, and stimulating environment where children feel loved, respected, and empowered to explore and discover.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Programs Section -->
<?php if (!empty($programs)): ?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Our Learning Programs</h2>
        <p class="text-center text-gray-600 mb-12">Discover the engaging programs that make learning fun and meaningful</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice($programs, 0, 6) as $program): ?>
                <div class="bg-gray-50 rounded-xl overflow-hidden shadow-lg card-hover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-3 text-purple-600"><?= e($program['name']) ?></h3>
                        <p class="text-gray-600"><?= str_limit(strip_tags($program['description']), 150) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?= url('/activities') ?>" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-full font-bold hover:bg-purple-700 transition">View All Programs</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Articles Section -->
<?php if (!empty($articles)): ?>
<section class="py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4">Latest News & Articles</h2>
        <p class="text-center text-gray-600 mb-12">Stay updated with our latest activities and educational insights</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($articles as $article): ?>
                <article class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                    <?php if ($article['featured_image']): ?>
                        <img src="<?= upload($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-indigo-600"></div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 hover:text-purple-600">
                            <a href="<?= url('/article/' . $article['slug']) ?>"><?= e($article['title']) ?></a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4"><?= format_date($article['published_at']) ?> • By <?= e($article['author_name']) ?></p>
                        <p class="text-gray-700"><?= str_limit($article['excerpt'] ?: strip_tags($article['content']), 120) ?></p>
                        <a href="<?= url('/article/' . $article['slug']) ?>" class="text-purple-600 font-semibold mt-4 inline-block hover:text-purple-700">Read More →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?= url('/articles') ?>" class="inline-block bg-purple-600 text-white px-8 py-3 rounded-full font-bold hover:bg-purple-700 transition">View All Articles</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="py-20 hero-gradient text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Give Your Child the Best Start?</h2>
        <p class="text-xl mb-8 text-purple-100 max-w-2xl mx-auto">Join our nurturing community and watch your child thrive in a Montessori environment designed for success.</p>
        <a href="<?= url('/registration') ?>" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition">Register Now</a>
    </div>
</section>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
