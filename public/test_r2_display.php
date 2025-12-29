<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test R2 Image Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .test-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .test-section h2 {
            margin-top: 0;
            color: #333;
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .status.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .status.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .status.info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .image-test {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .image-container {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .image-container img {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 10px auto;
        }
        .image-container .url {
            font-size: 12px;
            color: #666;
            word-break: break-all;
            margin-top: 10px;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <h1>🖼️ R2 Image Display Test</h1>
    
    <?php
    // Load config
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../app/helpers/functions.php';
    
    // Test URLs
    $testR2Url = 'https://pub-445331fec5ba4ec3af5c54ddc3ec1f4b.r2.dev/classes/test.jpg';
    $testLocalPath = 'uploads/test.jpg';
    ?>
    
    <div class="test-section">
        <h2>📋 Configuration Status</h2>
        <div class="status info">
            <strong>Environment:</strong> <?= APP_ENV ?><br>
            <strong>App URL:</strong> <?= APP_URL ?><br>
            <strong>R2 Enabled:</strong> <?= R2_ENABLED ? 'Yes ✅' : 'No ❌' ?><br>
            <strong>R2 Bucket:</strong> <?= R2_PUBLIC_BUCKET ?><br>
            <strong>R2 Public URL:</strong> <?= R2_PUBLIC_URL ?>
        </div>
    </div>
    
    <div class="test-section">
        <h2>🔧 Helper Function Test</h2>
        
        <h3>Test 1: R2 URL in Local Environment</h3>
        <div class="status info">
            <strong>Input:</strong> <code><?= htmlspecialchars($testR2Url) ?></code><br>
            <strong>Output:</strong> <code><?= htmlspecialchars(image_url($testR2Url)) ?></code><br>
            <strong>Expected:</strong> Should use proxy in local, direct URL in production<br>
            <?php
            $output = image_url($testR2Url);
            $usesProxy = strpos($output, 'proxy_r2_image.php') !== false;
            $isLocal = (APP_ENV === 'local' || strpos(APP_URL, 'localhost') !== false);
            
            if ($isLocal && $usesProxy) {
                echo '<strong>Result:</strong> <span style="color: green;">✅ PASS - Using proxy in local</span>';
            } elseif (!$isLocal && !$usesProxy) {
                echo '<strong>Result:</strong> <span style="color: green;">✅ PASS - Direct URL in production</span>';
            } else {
                echo '<strong>Result:</strong> <span style="color: red;">❌ FAIL - Unexpected behavior</span>';
            }
            ?>
        </div>
        
        <h3>Test 2: Local Upload Path</h3>
        <div class="status info">
            <strong>Input:</strong> <code><?= htmlspecialchars($testLocalPath) ?></code><br>
            <strong>Output:</strong> <code><?= htmlspecialchars(image_url($testLocalPath)) ?></code><br>
            <strong>Expected:</strong> Should convert to full URL<br>
            <?php
            $output = image_url($testLocalPath);
            $hasBaseUrl = strpos($output, APP_URL) !== false;
            
            if ($hasBaseUrl) {
                echo '<strong>Result:</strong> <span style="color: green;">✅ PASS - Converted to full URL</span>';
            } else {
                echo '<strong>Result:</strong> <span style="color: red;">❌ FAIL - Not converted properly</span>';
            }
            ?>
        </div>
        
        <h3>Test 3: Empty Input</h3>
        <div class="status info">
            <strong>Input:</strong> <code>''</code><br>
            <strong>Output:</strong> <code><?= htmlspecialchars(image_url('')) ?></code><br>
            <strong>Expected:</strong> Should return empty string<br>
            <?php
            $output = image_url('');
            
            if ($output === '') {
                echo '<strong>Result:</strong> <span style="color: green;">✅ PASS - Returns empty string</span>';
            } else {
                echo '<strong>Result:</strong> <span style="color: red;">❌ FAIL - Should return empty</span>';
            }
            ?>
        </div>
    </div>
    
    <div class="test-section">
        <h2>🌐 Proxy Script Test</h2>
        <?php
        $proxyPath = __DIR__ . '/proxy_r2_image.php';
        if (file_exists($proxyPath)) {
            echo '<div class="status success">✅ Proxy script exists at: <code>' . $proxyPath . '</code></div>';
            
            // Test proxy URL
            $proxyUrl = url('proxy_r2_image.php');
            echo '<div class="status info">';
            echo '<strong>Proxy URL:</strong> <code>' . htmlspecialchars($proxyUrl) . '</code><br>';
            echo '<strong>Test with:</strong> <code>' . htmlspecialchars($proxyUrl . '?url=' . urlencode($testR2Url)) . '</code>';
            echo '</div>';
        } else {
            echo '<div class="status error">❌ Proxy script NOT found at: <code>' . $proxyPath . '</code></div>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🎨 Visual Test</h2>
        <p>Replace the URLs below with actual R2 image URLs from your database to test display.</p>
        
        <?php
        // Get sample images from database
        try {
            $db = Database::getInstance()->getConnection();
            
            // Get sample class image
            $stmt = $db->query("SELECT title, image FROM classes LIMIT 1");
            $classData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($classData) {
                echo '<h3>Sample Class Image</h3>';
                echo '<div class="image-test">';
                
                echo '<div class="image-container">';
                echo '<h4>Raw URL from Database</h4>';
                echo '<img src="' . htmlspecialchars($classData['image']) . '" alt="' . htmlspecialchars($classData['title']) . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">';
                echo '<div style="display:none; color: red;">❌ Failed to load</div>';
                echo '<div class="url">' . htmlspecialchars($classData['image']) . '</div>';
                echo '</div>';
                
                echo '<div class="image-container">';
                echo '<h4>Using image_url() Helper</h4>';
                $processedUrl = image_url($classData['image']);
                echo '<img src="' . htmlspecialchars($processedUrl) . '" alt="' . htmlspecialchars($classData['title']) . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">';
                echo '<div style="display:none; color: red;">❌ Failed to load</div>';
                echo '<div class="url">' . htmlspecialchars($processedUrl) . '</div>';
                echo '</div>';
                
                echo '</div>';
            } else {
                echo '<div class="status info">ℹ️ No class images found in database. Add a class first to test.</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="status error">❌ Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>📝 Test Checklist</h2>
        <ul>
            <li>✅ Configuration loaded correctly</li>
            <li>✅ Helper function <code>image_url()</code> defined</li>
            <li>✅ Proxy script exists</li>
            <li>⏳ Upload test - Go to <a href="/admin/activities" target="_blank">/admin/activities</a> and upload an image</li>
            <li>⏳ Display test - Verify images display correctly on activities page</li>
            <li>⏳ Console test - Check browser console for errors</li>
            <li>⏳ Network test - Check network tab for proxy requests (local) or direct R2 (production)</li>
        </ul>
    </div>
    
    <div class="test-section">
        <h2>🚀 Next Steps</h2>
        <ol>
            <li>If this test page loads without errors: <strong style="color: green;">✅ Setup is correct</strong></li>
            <li>Go to <a href="/admin/activities" target="_blank">/admin/activities</a></li>
            <li>Click "Informasi Kelas" tab</li>
            <li>Click "Tambah Kelas" to add a new class with an image</li>
            <li>Verify the image displays correctly</li>
            <li>Open browser DevTools → Network tab</li>
            <li>In local: Verify image loads via <code>proxy_r2_image.php</code></li>
            <li>In production: Verify image loads directly from R2</li>
        </ol>
    </div>
    
</body>
</html>
