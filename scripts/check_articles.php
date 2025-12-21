<?php
/**
 * Script to check articles in database
 */

// Define ROOT_PATH
define('ROOT_PATH', dirname(__DIR__));

require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../config/config.php';

$db = Database::getInstance();

// Check if articles table exists
$tables = $db->query("SHOW TABLES LIKE 'articles'")->fetchAll();
if (empty($tables)) {
    echo "❌ Table 'articles' does not exist!\n";
    exit(1);
}

echo "✅ Table 'articles' exists\n\n";

// Count total articles
$total = $db->query("SELECT COUNT(*) as count FROM articles")->fetch();
echo "Total articles: " . $total['count'] . "\n\n";

// Count published articles
$published = $db->query("SELECT COUNT(*) as count FROM articles WHERE status = 'published' AND published_at <= NOW()")->fetch();
echo "Published articles: " . $published['count'] . "\n\n";

// Show all articles
$articles = $db->query("SELECT id, title, author_name, status, published_at, created_at FROM articles ORDER BY created_at DESC")->fetchAll();

if (empty($articles)) {
    echo "❌ No articles found in database!\n";
    echo "Please create articles through admin panel at /admin/articles\n";
} else {
    echo "Articles in database:\n";
    echo str_repeat("-", 120) . "\n";
    printf("%-5s %-40s %-20s %-12s %-20s\n", "ID", "Title", "Author", "Status", "Published At");
    echo str_repeat("-", 120) . "\n";
    
    foreach ($articles as $article) {
        printf(
            "%-5s %-40s %-20s %-12s %-20s\n",
            $article['id'],
            substr($article['title'], 0, 40),
            substr($article['author_name'], 0, 20),
            $article['status'],
            $article['published_at'] ?? 'Not published'
        );
    }
}

echo "\n";
