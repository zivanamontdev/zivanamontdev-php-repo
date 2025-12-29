<?php
/**
 * Simple Upload Test - Direct Testing
 * 
 * Test UploadManager directly without going through HTTP endpoints
 */

// Bootstrap
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

// Load composer autoload for AWS SDK
require_once ROOT_PATH . '/vendor/autoload.php';

require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/helpers/UploadManager.php';

echo str_repeat("=", 80) . "\n";
echo "UPLOAD MANAGER DIRECT TEST\n";
echo str_repeat("=", 80) . "\n\n";

// Create a test image
$testImagePath = __DIR__ . '/test_image.png';
$img = imagecreatetruecolor(100, 100);
$bgColor = imagecolorallocate($img, 255, 100, 100);
imagefill($img, 0, 0, $bgColor);
imagepng($img, $testImagePath);
imagedestroy($img);

echo "✅ Test image created: $testImagePath\n\n";

// Simulate $_FILES array
$_FILES['test_upload'] = [
    'name' => 'test_upload.png',
    'type' => 'image/png',
    'tmp_name' => $testImagePath,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($testImagePath)
];

echo "Testing file: {$_FILES['test_upload']['name']}\n";
echo "Size: " . number_format($_FILES['test_upload']['size']) . " bytes\n";
echo "Type: {$_FILES['test_upload']['type']}\n\n";

// Test 1: Validate File Type
echo "[TEST 1] Validate File Type\n";
$validation = UploadManager::validateFileType($_FILES['test_upload'], ['jpg', 'jpeg', 'png', 'gif']);
if ($validation['success']) {
    echo "✅ File type validation passed\n";
} else {
    echo "❌ File type validation failed: {$validation['message']}\n";
}
echo "\n";

// Test 2: Validate File Size
echo "[TEST 2] Validate File Size (max 5MB)\n";
$sizeValidation = UploadManager::validateFileSize($_FILES['test_upload'], 5242880);
if ($sizeValidation['success']) {
    echo "✅ File size validation passed\n";
} else {
    echo "❌ File size validation failed: {$sizeValidation['message']}\n";
}
echo "\n";

// Test 3: Upload File
echo "[TEST 3] Upload File to R2/Local\n";
$uploadResult = UploadManager::upload($_FILES['test_upload'], 'test_folder');
if ($uploadResult['success']) {
    echo "✅ Upload successful!\n";
    echo "   Path: {$uploadResult['path']}\n";
    echo "   Message: {$uploadResult['message']}\n";
    
    // Store path for cleanup
    $uploadedPath = $uploadResult['path'];
} else {
    echo "❌ Upload failed: {$uploadResult['message']}\n";
    if (isset($uploadResult['error'])) {
        echo "   Error: {$uploadResult['error']}\n";
    }
    $uploadedPath = null;
}
echo "\n";

// Test 4: Check if file exists (for local storage)
if ($uploadedPath && !R2_ENABLED) {
    echo "[TEST 4] Check Uploaded File (Local Storage)\n";
    $fullPath = ROOT_PATH . '/public' . $uploadedPath;
    if (file_exists($fullPath)) {
        echo "✅ File exists at: $fullPath\n";
        echo "   Size: " . number_format(filesize($fullPath)) . " bytes\n";
    } else {
        echo "❌ File not found at: $fullPath\n";
    }
    echo "\n";
}

// Test 5: Replace file (upload again with old path)
if ($uploadedPath) {
    echo "[TEST 5] Replace Uploaded File\n";
    
    // Create another test image
    $testImage2Path = __DIR__ . '/test_image2.png';
    $img2 = imagecreatetruecolor(150, 150);
    $bgColor2 = imagecolorallocate($img2, 100, 255, 100);
    imagefill($img2, 0, 0, $bgColor2);
    imagepng($img2, $testImage2Path);
    imagedestroy($img2);
    
    $_FILES['test_upload2'] = [
        'name' => 'test_upload2.png',
        'type' => 'image/png',
        'tmp_name' => $testImage2Path,
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($testImage2Path)
    ];
    
    $replaceResult = UploadManager::upload($_FILES['test_upload2'], 'test_folder', $uploadedPath);
    if ($replaceResult['success']) {
        echo "✅ Replace successful!\n";
        echo "   New path: {$replaceResult['path']}\n";
        echo "   Old file should be deleted\n";
        
        $uploadedPath = $replaceResult['path'];
    } else {
        echo "❌ Replace failed: {$replaceResult['message']}\n";
    }
    
    // Clean up test image 2
    if (file_exists($testImage2Path)) {
        unlink($testImage2Path);
    }
    echo "\n";
}

// Test 6: Delete File
if ($uploadedPath) {
    echo "[TEST 6] Delete Uploaded File\n";
    $deleteResult = UploadManager::delete($uploadedPath);
    if ($deleteResult) {
        echo "✅ Delete successful!\n";
        
        // Check if file still exists (for local)
        if (!R2_ENABLED) {
            $fullPath = ROOT_PATH . '/public' . $uploadedPath;
            if (!file_exists($fullPath)) {
                echo "✅ File confirmed deleted from local storage\n";
            } else {
                echo "❌ File still exists at: $fullPath\n";
            }
        }
    } else {
        echo "❌ Delete failed\n";
    }
    echo "\n";
}

// Cleanup
if (file_exists($testImagePath)) {
    unlink($testImagePath);
}

// Summary
echo str_repeat("=", 80) . "\n";
echo "CONFIGURATION\n";
echo str_repeat("=", 80) . "\n";
echo "R2_ENABLED: " . (R2_ENABLED ? 'YES' : 'NO') . "\n";
if (R2_ENABLED) {
    echo "R2_ENDPOINT: " . R2_ENDPOINT . "\n";
    echo "R2_PUBLIC_BUCKET: " . R2_PUBLIC_BUCKET . "\n";
    echo "R2_PUBLIC_URL: " . R2_PUBLIC_URL . "\n";
} else {
    echo "LOCAL_UPLOAD_PATH: " . UPLOAD_PATH . "\n";
}
echo str_repeat("=", 80) . "\n";
echo "All tests completed!\n";
echo str_repeat("=", 80) . "\n";
