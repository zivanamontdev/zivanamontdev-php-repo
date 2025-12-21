<?php
/**
 * Test Articles Page Output
 */

// Define ROOT_PATH
define('ROOT_PATH', dirname(__DIR__));

// Load config and helpers
require ROOT_PATH . '/config/config.php';
require ROOT_PATH . '/app/helpers/functions.php';
require ROOT_PATH . '/app/core/Database.php';
require ROOT_PATH . '/app/core/Model.php';
require ROOT_PATH . '/app/models/Article.php';

echo "Testing Articles Page Data...\n\n";

// Simulate what HomeController does
$articleModel = new Article();
$page = 1;
$pagination = $articleModel->paginate($page, 9, 'status = :status AND published_at <= NOW()', 
    ['status' => 'published'], 'published_at DESC');

echo "Articles found: " . count($pagination['data']) . "\n";
echo "Total pages: " . $pagination['total_pages'] . "\n";
echo "Current page: " . $pagination['current_page'] . "\n\n";

if (!empty($pagination['data'])) {
    echo "First 3 articles (Grid):\n";
    echo str_repeat("-", 100) . "\n";
    
    $gridArticles = array_slice($pagination['data'], 0, 3);
    foreach ($gridArticles as $i => $article) {
        echo ($i + 1) . ". " . $article['title'] . "\n";
        echo "   Author: " . $article['author_name'] . "\n";
        echo "   Published: " . $article['published_at'] . "\n";
        echo "   Image: " . ($article['featured_image'] ?? 'none') . "\n";
        echo "   Excerpt: " . substr($article['excerpt'], 0, 80) . "...\n\n";
    }
    
    $listArticles = array_slice($pagination['data'], 3);
    if (!empty($listArticles)) {
        echo "\nRemaining articles (List):\n";
        echo str_repeat("-", 100) . "\n";
        foreach ($listArticles as $i => $article) {
            echo ($i + 4) . ". " . $article['title'] . "\n";
        }
    }
} else {
    echo "❌ No published articles found!\n";
}

echo "\n✅ Test completed!\n";
