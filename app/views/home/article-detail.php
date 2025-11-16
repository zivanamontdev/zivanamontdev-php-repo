<?php 
$pageTitle = $article['title'];
ob_start(); 
?>

<!-- Article Header -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <a href="<?= url('/articles') ?>" class="text-purple-600 hover:text-purple-700 mb-4 inline-block">← Back to Articles</a>
            
            <?php if ($article['is_featured']): ?>
                <span class="inline-block bg-purple-600 text-white text-sm px-4 py-1 rounded-full mb-4">Featured</span>
            <?php endif; ?>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-6"><?= e($article['title']) ?></h1>
            
            <div class="flex items-center text-gray-600 mb-8">
                <span><?= format_date($article['published_at']) ?></span>
                <span class="mx-3">•</span>
                <span>By <?= e($article['author_name']) ?></span>
                <span class="mx-3">•</span>
                <span><?= $article['views'] ?> views</span>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <article class="max-w-4xl mx-auto">
            <?php if ($article['featured_image']): ?>
                <img src="<?= upload($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" class="w-full rounded-xl mb-8 shadow-lg">
            <?php endif; ?>
            
            <div class="prose prose-lg max-w-none">
                <?= $article['content'] ?>
            </div>
        </article>
    </div>
</section>

<!-- Related Articles -->
<?php if (!empty($relatedArticles)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold mb-8">Related Articles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($relatedArticles as $related): ?>
                    <?php if ($related['id'] != $article['id']): ?>
                        <article class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                            <?php if ($related['featured_image']): ?>
                                <img src="<?= upload($related['featured_image']) ?>" alt="<?= e($related['title']) ?>" class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-indigo-600"></div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <h3 class="text-lg font-bold mb-2 hover:text-purple-600">
                                    <a href="<?= url('/article/' . $related['slug']) ?>"><?= e($related['title']) ?></a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-3"><?= format_date($related['published_at']) ?></p>
                                <a href="<?= url('/article/' . $related['slug']) ?>" class="text-purple-600 font-semibold hover:text-purple-700">Read More →</a>
                            </div>
                        </article>
                    <?php endif; ?>
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
