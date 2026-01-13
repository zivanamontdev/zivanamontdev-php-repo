<?php
require_once '../app/core/Database.php';
require_once '../config/config.php';

$db = Database::getInstance();

echo "<h2>Fix Published Articles - Set published_at</h2>";

// Find all published articles without published_at
$articles = $db->fetchAll("SELECT id, title, status, published_at, created_at FROM articles WHERE status = 'published' AND (published_at IS NULL OR published_at = '0000-00-00 00:00:00')");

echo "<p>Found " . count($articles) . " published articles without valid published_at</p>";

if (count($articles) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Old Published At</th><th>New Published At</th><th>Action</th></tr>";
    
    foreach ($articles as $article) {
        // Use created_at as published_at
        $newPublishedAt = $article['created_at'];
        
        $db->query(
            "UPDATE articles SET published_at = :published_at WHERE id = :id",
            ['published_at' => $newPublishedAt, 'id' => $article['id']]
        );
        
        echo "<tr>";
        echo "<td>{$article['id']}</td>";
        echo "<td>{$article['title']}</td>";
        echo "<td>{$article['status']}</td>";
        echo "<td>" . ($article['published_at'] ?: 'NULL') . "</td>";
        echo "<td>$newPublishedAt</td>";
        echo "<td style='color: green;'>✓ Fixed</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "<p style='color: green; font-weight: bold;'>✓ All articles have been fixed!</p>";
} else {
    echo "<p style='color: green;'>✓ All published articles already have valid published_at dates</p>";
}

echo "<hr>";
echo "<h3>Verification</h3>";

// Verify the fix
$verifyPublished = $db->fetchAll("SELECT id, title, status, published_at FROM articles WHERE status = 'published' ORDER BY published_at DESC");
echo "<p>Published articles that will show on public page (status='published' AND published_at <= NOW()): " . count($verifyPublished) . "</p>";

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Published At</th></tr>";
foreach ($verifyPublished as $article) {
    $isValid = !empty($article['published_at']) && $article['published_at'] !== '0000-00-00 00:00:00' && strtotime($article['published_at']) <= time();
    $bgColor = $isValid ? '#d4edda' : '#f8d7da';
    
    echo "<tr style='background: $bgColor;'>";
    echo "<td>{$article['id']}</td>";
    echo "<td>{$article['title']}</td>";
    echo "<td>{$article['status']}</td>";
    echo "<td>{$article['published_at']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><a href='/articles'>View Public Articles Page</a></p>";
