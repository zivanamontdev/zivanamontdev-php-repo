<?php
/**
 * Debug Upload Test - Untuk tracking issue upload R2
 * 
 * Test file untuk debug upload class image
 */

require_once __DIR__ . '/config/config.php';
require_once APP_PATH . '/helpers/UploadManager.php';

header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>";
echo "====================================\n";
echo "DEBUG UPLOAD TEST\n";
echo "====================================\n\n";

// 1. Check Configuration
echo "1. CONFIGURATION CHECK:\n";
echo "   R2_ENABLED: " . (R2_ENABLED ? 'true' : 'false') . "\n";
if (R2_ENABLED) {
    echo "   R2_ENDPOINT: " . (defined('R2_ENDPOINT') ? R2_ENDPOINT : 'NOT SET') . "\n";
    echo "   R2_PUBLIC_BUCKET: " . (defined('R2_PUBLIC_BUCKET') ? R2_PUBLIC_BUCKET : 'NOT SET') . "\n";
    echo "   R2_PUBLIC_URL: " . (defined('R2_PUBLIC_URL') ? R2_PUBLIC_URL : 'NOT SET') . "\n";
    echo "   R2_ACCESS_KEY_ID: " . (defined('R2_ACCESS_KEY_ID') && !empty(R2_ACCESS_KEY_ID) ? 'SET (***hidden***)' : 'NOT SET') . "\n";
    echo "   R2_SECRET_ACCESS_KEY: " . (defined('R2_SECRET_ACCESS_KEY') && !empty(R2_SECRET_ACCESS_KEY) ? 'SET (***hidden***)' : 'NOT SET') . "\n";
}
echo "\n";

// 2. Check if this is a POST request with file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "2. FILE UPLOAD DETECTED:\n";
    echo "   File Name: " . $_FILES['test_image']['name'] . "\n";
    echo "   File Type: " . $_FILES['test_image']['type'] . "\n";
    echo "   File Size: " . $_FILES['test_image']['size'] . " bytes (" . round($_FILES['test_image']['size'] / 1024, 2) . " KB)\n";
    echo "   File Error: " . $_FILES['test_image']['error'] . "\n";
    echo "   Tmp Name: " . $_FILES['test_image']['tmp_name'] . "\n";
    echo "\n";
    
    // 3. Try to upload
    echo "3. ATTEMPTING UPLOAD:\n";
    try {
        // Validate file type
        $validation = UploadManager::validateFileType($_FILES['test_image'], ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        echo "   File Type Validation: " . ($validation['success'] ? 'PASS' : 'FAIL - ' . $validation['message']) . "\n";
        
        if (!$validation['success']) {
            throw new Exception($validation['message']);
        }
        
        // Validate file size
        $sizeValidation = UploadManager::validateFileSize($_FILES['test_image'], 5242880);
        echo "   File Size Validation: " . ($sizeValidation['success'] ? 'PASS' : 'FAIL - ' . $sizeValidation['message']) . "\n";
        
        if (!$sizeValidation['success']) {
            throw new Exception($sizeValidation['message']);
        }
        
        // Upload
        echo "\n   Uploading to " . (R2_ENABLED ? 'R2' : 'Local') . "...\n";
        $result = UploadManager::upload($_FILES['test_image'], 'classes');
        
        echo "\n4. UPLOAD RESULT:\n";
        echo "   Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
        echo "   Message: " . ($result['message'] ?? 'No message') . "\n";
        
        if ($result['success']) {
            echo "   Path/URL: " . $result['path'] . "\n";
            echo "\n5. VERIFY URL:\n";
            
            // Try to access the URL
            if (R2_ENABLED && strpos($result['path'], 'http') === 0) {
                echo "   Testing URL accessibility...\n";
                
                $ch = curl_init($result['path']);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);
                
                echo "   HTTP Status Code: " . $httpCode . "\n";
                if ($httpCode === 200) {
                    echo "   ✅ File is ACCESSIBLE!\n";
                } else {
                    echo "   ❌ File is NOT accessible (HTTP " . $httpCode . ")\n";
                    if ($curlError) {
                        echo "   cURL Error: " . $curlError . "\n";
                    }
                }
            } else {
                // Local file
                $fullPath = __DIR__ . '/public/' . $result['path'];
                echo "   Full Path: " . $fullPath . "\n";
                echo "   File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
                if (file_exists($fullPath)) {
                    echo "   File Size: " . filesize($fullPath) . " bytes\n";
                    echo "   ✅ File uploaded successfully!\n";
                }
            }
            
            // Try to delete the test file
            echo "\n6. CLEANUP:\n";
            $deleted = UploadManager::delete($result['path']);
            echo "   Delete Result: " . ($deleted ? 'SUCCESS' : 'FAILED') . "\n";
        }
        
    } catch (Exception $e) {
        echo "\n❌ ERROR OCCURRED:\n";
        echo "   Exception: " . $e->getMessage() . "\n";
        echo "   File: " . $e->getFile() . "\n";
        echo "   Line: " . $e->getLine() . "\n";
        echo "\n   Stack Trace:\n";
        echo $e->getTraceAsString() . "\n";
    }
    
} else {
    // Show upload form
    echo "2. NO FILE UPLOADED\n";
    echo "   Use the form below to test upload:\n\n";
    echo "</pre>";
    ?>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .upload-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .upload-box h2 {
            margin-top: 0;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="file"]:hover {
            border-color: #007bff;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background: #0056b3;
        }
        .info {
            background: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .config-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
    
    <div class="upload-box">
        <h2>🔍 R2 Upload Debug Test</h2>
        
        <div class="config-info">
            <strong>Current Configuration:</strong><br>
            R2_ENABLED: <?= R2_ENABLED ? '✅ TRUE' : '❌ FALSE' ?><br>
            <?php if (R2_ENABLED): ?>
            R2_PUBLIC_BUCKET: <?= R2_PUBLIC_BUCKET ?><br>
            R2_PUBLIC_URL: <?= R2_PUBLIC_URL ?><br>
            <?php else: ?>
            Upload Mode: LOCAL STORAGE<br>
            Upload Path: public/uploads/
            <?php endif; ?>
        </div>
        
        <div class="info">
            <strong>📌 Test Instructions:</strong>
            <ol>
                <li>Select an image file (JPG, PNG, GIF, or WebP)</li>
                <li>Click "Test Upload" button</li>
                <li>Check the debug output above</li>
                <li>Verify if the file is accessible</li>
            </ol>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="test_image">Select Image File:</label>
                <input type="file" id="test_image" name="test_image" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn">Test Upload</button>
        </form>
    </div>
    
    <?php
}

echo "</pre>";
