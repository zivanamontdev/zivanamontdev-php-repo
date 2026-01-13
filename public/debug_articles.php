<?php
require_once '../app/core/Database.php';
require_once '../config/config.php';

$db = Database::getInstance();

echo "<h2>Debug Articles Query</h2>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";

// Query 1: All articles
$allArticles = $db->fetchAll("SELECT id, title, status, published_at, created_at FROM articles ORDER BY published_at DESC");
echo "<h3>All Articles (Total: " . count($allArticles) . ")</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
foreach ($allArticles as $article) {
    echo "<tr>";
    echo "<td>{$article['id']}</td>";
    echo "<td>{$article['title']}</td>";
    echo "<td>{$article['status']}</td>";
    echo "<td>{$article['published_at']}</td>";
    echo "<td>{$article['created_at']}</td>";
    echo "</tr>";
}
echo "</table>";

// Query 2: Published articles with time filter
$publishedArticles = $db->fetchAll("SELECT id, title, status, published_at, created_at FROM articles WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC");
echo "<h3>Published Articles (with time filter) (Total: " . count($publishedArticles) . ")</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
foreach ($publishedArticles as $article) {
    echo "<tr>";
    echo "<td>{$article['id']}</td>";
    echo "<td>{$article['title']}</td>";
    echo "<td>{$article['status']}</td>";
    echo "<td>{$article['published_at']}</td>";
    echo "<td>{$article['created_at']}</td>";
    echo "</tr>";
}
echo "</table>";

// Query 3: Articles with published_at in the future
$futureArticles = $db->fetchAll("SELECT id, title, status, published_at, created_at FROM articles WHERE status = 'published' AND published_at > NOW() ORDER BY published_at DESC");
echo "<h3>Published Articles with Future Date (Total: " . count($futureArticles) . ")</h3>";
if (count($futureArticles) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Published At</th><th>Created At</th></tr>";
    foreach ($futureArticles as $article) {
        echo "<tr style='background: #ffe0e0;'>";
        echo "<td>{$article['id']}</td>";
        echo "<td>{$article['title']}</td>";
        echo "<td>{$article['status']}</td>";
        echo "<td>{$article['published_at']}</td>";
        echo "<td>{$article['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No articles with future published date</p>";
}
