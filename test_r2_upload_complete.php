<?php
/**
 * Test Upload Migration to R2 - Complete Test Suite
 * 
 * Test file untuk memastikan semua upload functionality bekerja dengan baik
 * baik di local maupun di R2 environment
 * 
 * @author Zivana Montessori Dev Team
 * @version 1.0
 */

require_once __DIR__ . '/config/config.php';
require_once APP_PATH . '/helpers/UploadManager.php';

// Test configuration
$testResults = [];
$testsPassed = 0;
$testsFailed = 0;

// Color codes for terminal output
$GREEN = "\033[32m";
$RED = "\033[31m";
$YELLOW = "\033[33m";
$BLUE = "\033[34m";
$RESET = "\033[0m";

/**
 * Helper function to log test results
 */
function logTest($name, $passed, $message = '') {
    global $testResults, $testsPassed, $testsFailed, $GREEN, $RED, $RESET;
    
    if ($passed) {
        $testsPassed++;
        echo $GREEN . "✓ PASS: " . $RESET . $name . "\n";
    } else {
        $testsFailed++;
        echo $RED . "✗ FAIL: " . $RESET . $name . "\n";
        if ($message) {
            echo "  → " . $message . "\n";
        }
    }
    
    $testResults[] = [
        'name' => $name,
        'passed' => $passed,
        'message' => $message
    ];
}

/**
 * Create a test image file
 */
function createTestImage($filename = 'test_image.jpg') {
    $tmpDir = sys_get_temp_dir();
    $testImagePath = $tmpDir . '/' . $filename;
    
    // Create a simple 100x100 red image
    $image = imagecreate(100, 100);
    $red = imagecolorallocate($image, 255, 0, 0);
    imagefill($image, 0, 0, $red);
    imagejpeg($image, $testImagePath);
    imagedestroy($image);
    
    return $testImagePath;
}

/**
 * Simulate $_FILES array
 */
function createFilesArray($testImagePath, $originalName = 'test.jpg') {
    return [
        'name' => $originalName,
        'type' => 'image/jpeg',
        'tmp_name' => $testImagePath,
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($testImagePath)
    ];
}

echo "\n";
echo "=======================================================\n";
echo "  R2 UPLOAD MIGRATION - COMPLETE TEST SUITE\n";
echo "=======================================================\n\n";

echo $BLUE . "Environment: " . $RESET;
echo (R2_ENABLED ? "R2 ENABLED ✓" : "LOCAL MODE ✓") . "\n\n";

// ==============================================================
// TEST 1: Configuration Check
// ==============================================================
echo $YELLOW . "TEST GROUP 1: Configuration Check" . $RESET . "\n";
echo "-------------------------------------------------------\n";

logTest(
    "R2_ENABLED constant is defined",
    defined('R2_ENABLED'),
    "Check config/config.php"
);

logTest(
    "UPLOAD_PATH constant is defined",
    defined('UPLOAD_PATH'),
    "Check config/config.php"
);

if (R2_ENABLED) {
    logTest(
        "R2_ENDPOINT is configured",
        defined('R2_ENDPOINT') && !empty(R2_ENDPOINT),
        "R2_ENDPOINT: " . (defined('R2_ENDPOINT') ? R2_ENDPOINT : 'NOT SET')
    );
    
    logTest(
        "R2_ACCESS_KEY_ID is configured",
        defined('R2_ACCESS_KEY_ID') && !empty(R2_ACCESS_KEY_ID),
        "Check .env file"
    );
    
    logTest(
        "R2_SECRET_ACCESS_KEY is configured",
        defined('R2_SECRET_ACCESS_KEY') && !empty(R2_SECRET_ACCESS_KEY),
        "Check .env file"
    );
    
    logTest(
        "R2_PUBLIC_BUCKET is configured",
        defined('R2_PUBLIC_BUCKET') && !empty(R2_PUBLIC_BUCKET),
        "R2_PUBLIC_BUCKET: " . (defined('R2_PUBLIC_BUCKET') ? R2_PUBLIC_BUCKET : 'NOT SET')
    );
    
    logTest(
        "R2_PUBLIC_URL is configured",
        defined('R2_PUBLIC_URL') && !empty(R2_PUBLIC_URL),
        "R2_PUBLIC_URL: " . (defined('R2_PUBLIC_URL') ? R2_PUBLIC_URL : 'NOT SET')
    );
}

echo "\n";

// ==============================================================
// TEST 2: UploadManager Class Check
// ==============================================================
echo $YELLOW . "TEST GROUP 2: UploadManager Class Check" . $RESET . "\n";
echo "-------------------------------------------------------\n";

logTest(
    "UploadManager class exists",
    class_exists('UploadManager'),
    "Check app/helpers/UploadManager.php"
);

logTest(
    "UploadManager::upload method exists",
    method_exists('UploadManager', 'upload'),
    "Check UploadManager class"
);

logTest(
    "UploadManager::delete method exists",
    method_exists('UploadManager', 'delete'),
    "Check UploadManager class"
);

logTest(
    "UploadManager::validateFileType method exists",
    method_exists('UploadManager', 'validateFileType'),
    "Check UploadManager class"
);

logTest(
    "UploadManager::validateFileSize method exists",
    method_exists('UploadManager', 'validateFileSize'),
    "Check UploadManager class"
);

logTest(
    "UploadManager::getUrl method exists",
    method_exists('UploadManager', 'getUrl'),
    "Check UploadManager class"
);

echo "\n";

// ==============================================================
// TEST 3: File Validation Tests
// ==============================================================
echo $YELLOW . "TEST GROUP 3: File Validation Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

// Test file type validation
$testImagePath = createTestImage('valid_test.jpg');
$validFile = createFilesArray($testImagePath, 'test.jpg');

$validation = UploadManager::validateFileType($validFile, ['jpg', 'jpeg', 'png', 'gif']);
logTest(
    "Valid file type (JPG) is accepted",
    $validation['success'],
    $validation['message']
);

// Test invalid file type
$invalidFile = createFilesArray($testImagePath, 'test.exe');
$invalidFile['type'] = 'application/x-msdownload';
$validation = UploadManager::validateFileType($invalidFile, ['jpg', 'jpeg', 'png']);
logTest(
    "Invalid file type (EXE) is rejected",
    !$validation['success'],
    $validation['message']
);

// Test file size validation
$sizeValidation = UploadManager::validateFileSize($validFile, 1048576); // 1MB
logTest(
    "Small file passes size validation",
    $sizeValidation['success'],
    $sizeValidation['message']
);

// Test file too large
$largeFile = $validFile;
$largeFile['size'] = 10485760; // 10MB
$sizeValidation = UploadManager::validateFileSize($largeFile, 5242880); // Max 5MB
logTest(
    "Large file fails size validation",
    !$sizeValidation['success'],
    $sizeValidation['message']
);

echo "\n";

// ==============================================================
// TEST 4: Upload Functionality Tests
// ==============================================================
echo $YELLOW . "TEST GROUP 4: Upload Functionality Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

// Create a new test image for upload
$uploadTestImage = createTestImage('upload_test.jpg');
$uploadFile = createFilesArray($uploadTestImage, 'upload_test.jpg');

// Test upload
$uploadResult = UploadManager::upload($uploadFile, 'test_uploads');
logTest(
    "File upload successful",
    $uploadResult['success'],
    $uploadResult['message'] ?? ''
);

$uploadedPath = null;
if ($uploadResult['success']) {
    $uploadedPath = $uploadResult['path'];
    
    // Verify uploaded path format
    if (R2_ENABLED) {
        $isValidUrl = (strpos($uploadedPath, 'http://') === 0 || strpos($uploadedPath, 'https://') === 0);
        logTest(
            "R2 upload returns valid URL",
            $isValidUrl,
            "Path: " . $uploadedPath
        );
    } else {
        $isValidPath = (strpos($uploadedPath, 'uploads/') === 0);
        logTest(
            "Local upload returns valid path",
            $isValidPath,
            "Path: " . $uploadedPath
        );
    }
    
    // Test getUrl method
    $fullUrl = UploadManager::getUrl($uploadedPath);
    logTest(
        "getUrl returns valid URL",
        !empty($fullUrl),
        "URL: " . $fullUrl
    );
}

echo "\n";

// ==============================================================
// TEST 5: Replace/Update File Tests
// ==============================================================
echo $YELLOW . "TEST GROUP 5: Replace/Update File Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

if ($uploadedPath) {
    // Upload a replacement file
    $replaceTestImage = createTestImage('replace_test.jpg');
    $replaceFile = createFilesArray($replaceTestImage, 'replace_test.jpg');
    
    $replaceResult = UploadManager::upload($replaceFile, 'test_uploads', $uploadedPath);
    logTest(
        "File replacement successful",
        $replaceResult['success'],
        $replaceResult['message'] ?? ''
    );
    
    if ($replaceResult['success']) {
        $newPath = $replaceResult['path'];
        logTest(
            "New file path is different from old path",
            $newPath !== $uploadedPath,
            "Old: " . $uploadedPath . "\nNew: " . $newPath
        );
        
        // Update uploadedPath for deletion test
        $uploadedPath = $newPath;
    }
} else {
    logTest(
        "Skip replacement test",
        false,
        "Previous upload failed"
    );
}

echo "\n";

// ==============================================================
// TEST 6: Delete Functionality Tests
// ==============================================================
echo $YELLOW . "TEST GROUP 6: Delete Functionality Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

if ($uploadedPath) {
    $deleteResult = UploadManager::delete($uploadedPath);
    logTest(
        "File deletion successful",
        $deleteResult,
        "Deleted: " . $uploadedPath
    );
} else {
    logTest(
        "Skip deletion test",
        false,
        "No file uploaded to delete"
    );
}

// Test deleting non-existent file
$deleteNonExistent = UploadManager::delete('uploads/non_existent_file.jpg');
logTest(
    "Deleting non-existent file returns false",
    !$deleteNonExistent,
    "Expected: false"
);

echo "\n";

// ==============================================================
// TEST 7: Different Folder Tests
// ==============================================================
echo $YELLOW . "TEST GROUP 7: Different Folder Upload Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

$folders = ['classes', 'programs_tahun', 'events', 'testimonials', 'highlights'];
$uploadedFiles = [];

foreach ($folders as $folder) {
    $testImage = createTestImage('test_' . $folder . '.jpg');
    $testFile = createFilesArray($testImage, 'test_' . $folder . '.jpg');
    
    $result = UploadManager::upload($testFile, $folder);
    logTest(
        "Upload to '$folder' folder successful",
        $result['success'],
        $result['message'] ?? ''
    );
    
    if ($result['success']) {
        $uploadedFiles[] = $result['path'];
    }
}

echo "\n";

// ==============================================================
// TEST 8: Cleanup Test Files
// ==============================================================
echo $YELLOW . "TEST GROUP 8: Cleanup Test Files" . $RESET . "\n";
echo "-------------------------------------------------------\n";

// Delete all uploaded test files
foreach ($uploadedFiles as $filePath) {
    $deleted = UploadManager::delete($filePath);
    if ($deleted) {
        echo "  → Cleaned up: " . basename($filePath) . "\n";
    }
}

// Clean up temp files
$tempFiles = [
    sys_get_temp_dir() . '/valid_test.jpg',
    sys_get_temp_dir() . '/upload_test.jpg',
    sys_get_temp_dir() . '/replace_test.jpg'
];

foreach ($tempFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
    }
}

foreach ($folders as $folder) {
    $file = sys_get_temp_dir() . '/test_' . $folder . '.jpg';
    if (file_exists($file)) {
        unlink($file);
    }
}

logTest(
    "Cleanup completed",
    true,
    "All test files removed"
);

echo "\n";

// ==============================================================
// TEST SUMMARY
// ==============================================================
echo "=======================================================\n";
echo "  TEST SUMMARY\n";
echo "=======================================================\n\n";

$totalTests = $testsPassed + $testsFailed;
$passRate = $totalTests > 0 ? round(($testsPassed / $totalTests) * 100, 2) : 0;

echo "Total Tests: " . $totalTests . "\n";
echo $GREEN . "Passed: " . $testsPassed . $RESET . "\n";
echo $RED . "Failed: " . $testsFailed . $RESET . "\n";
echo "Pass Rate: " . $passRate . "%\n\n";

if ($testsFailed === 0) {
    echo $GREEN . "🎉 ALL TESTS PASSED! 🎉" . $RESET . "\n";
    echo "Your R2 upload migration is working correctly!\n";
} else {
    echo $RED . "⚠️  SOME TESTS FAILED ⚠️" . $RESET . "\n";
    echo "Please review the failed tests above and fix the issues.\n";
}

echo "\n";
echo "=======================================================\n";
echo "  RECOMMENDATIONS\n";
echo "=======================================================\n\n";

if (R2_ENABLED) {
    echo "✓ R2 is enabled. Your uploads are going to Cloudflare R2.\n";
    echo "  - Make sure your R2 bucket is properly configured\n";
    echo "  - Check that your public URL is accessible\n";
    echo "  - Monitor your R2 usage in Cloudflare dashboard\n";
} else {
    echo "✓ Local storage mode is active.\n";
    echo "  - Uploads are saved to: " . UPLOAD_PATH . "\n";
    echo "  - To enable R2, set R2_ENABLED=true in .env\n";
    echo "  - Configure R2 credentials in .env file\n";
}

echo "\n";
echo "Next Steps:\n";
echo "1. Test actual upload endpoints via browser/Postman\n";
echo "2. Verify files appear in correct location (R2 or local)\n";
echo "3. Test file deletion through admin interface\n";
echo "4. Monitor error logs for any issues\n";
echo "\n";

// Return exit code based on test results
exit($testsFailed > 0 ? 1 : 0);
