<?php 
$pageTitle = 'Articles';
ob_start(); 
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-center">News & Articles</h1>
        <p class="text-center text-purple-100 mt-4 text-lg">Stay updated with our latest activities and educational insights</p>
    </div>
</section>

<!-- Articles Grid -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <?php if (!empty($articles)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($articles as $article): ?>
                    <article class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                        <?php if ($article['featured_image']): ?>
                            <img src="<?= upload($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" class="w-full h-56 object-cover">
                        <?php else: ?>
                            <div class="w-full h-56 bg-gradient-to-br from-purple-400 to-indigo-600"></div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if ($article['is_featured']): ?>
                                <span class="inline-block bg-purple-600 text-white text-xs px-3 py-1 rounded-full mb-3">Featured</span>
                            <?php endif; ?>
                            
                            <h3 class="text-xl font-bold mb-2 hover:text-purple-600">
                                <a href="<?= url('/article/' . $article['slug']) ?>"><?= e($article['title']) ?></a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-4">
                                <?= format_date($article['published_at']) ?> • By <?= e($article['author_name']) ?>
                            </p>
                            
                            <p class="text-gray-700 mb-4"><?= str_limit($article['excerpt'] ?: strip_tags($article['content']), 150) ?></p>
                            
                            <a href="<?= url('/article/' . $article['slug']) ?>" class="text-purple-600 font-semibold hover:text-purple-700">Read More →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['last_page'] > 1): ?>
                <div class="mt-12 flex justify-center space-x-2">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <?php if ($i == $pagination['current_page']): ?>
                            <span class="px-4 py-2 bg-purple-600 text-white rounded-lg"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                        <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No articles available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php component('footer', ['showCta' => true]); ?>

<?php 
$content = ob_get_clean();
require VIEW_PATH . '/layouts/main.php';
?>
