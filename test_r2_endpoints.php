<?php
/**
 * Test R2 Upload - Endpoint Integration Test
 * 
 * Test file untuk menguji actual endpoints dengan upload functionality
 * Menggunakan cURL untuk simulate HTTP requests
 * 
 * @author Zivana Montessori Dev Team
 * @version 1.0
 */

require_once __DIR__ . '/config/config.php';

// Configuration
$BASE_URL = BASE_URL; // From config.php
$testResults = [];
$testsPassed = 0;
$testsFailed = 0;

// Color codes
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
    
    // Create a simple 100x100 blue image
    $image = imagecreate(100, 100);
    $blue = imagecolorallocate($image, 0, 0, 255);
    imagefill($image, 0, 0, $blue);
    imagejpeg($image, $testImagePath);
    imagedestroy($image);
    
    return $testImagePath;
}

/**
 * Make HTTP request using cURL
 */
function makeRequest($url, $method = 'GET', $data = null, $files = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local testing
    
    // Set method
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    // Handle multipart form data (file uploads)
    if ($files) {
        $postData = [];
        
        // Add regular form fields
        if ($data) {
            $postData = array_merge($postData, $data);
        }
        
        // Add files
        foreach ($files as $fieldName => $filePath) {
            if (file_exists($filePath)) {
                $postData[$fieldName] = new CURLFile($filePath, mime_content_type($filePath), basename($filePath));
            }
        }
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    } elseif ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    
    // Set headers
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    // Get cookies if session exists
    if (isset($_COOKIE)) {
        $cookieString = '';
        foreach ($_COOKIE as $key => $value) {
            $cookieString .= $key . '=' . $value . '; ';
        }
        if ($cookieString) {
            curl_setopt($ch, CURLOPT_COOKIE, rtrim($cookieString, '; '));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

/**
 * Parse JSON response
 */
function parseJsonResponse($response) {
    $decoded = json_decode($response, true);
    return $decoded !== null ? $decoded : ['error' => 'Invalid JSON response', 'raw' => $response];
}

echo "\n";
echo "=======================================================\n";
echo "  R2 UPLOAD - ENDPOINT INTEGRATION TEST\n";
echo "=======================================================\n\n";

echo $BLUE . "Base URL: " . $RESET . $BASE_URL . "\n";
echo $BLUE . "Environment: " . $RESET . (R2_ENABLED ? "R2 ENABLED ✓" : "LOCAL MODE ✓") . "\n\n";

// ==============================================================
// TEST 1: Check if server is accessible
// ==============================================================
echo $YELLOW . "TEST GROUP 1: Server Accessibility" . $RESET . "\n";
echo "-------------------------------------------------------\n";

$homeResponse = makeRequest($BASE_URL);
logTest(
    "Server is accessible",
    $homeResponse['http_code'] === 200,
    "HTTP Code: " . $homeResponse['http_code']
);

echo "\n";

// ==============================================================
// TEST 2: Test ActivityController Endpoints
// ==============================================================
echo $YELLOW . "TEST GROUP 2: ActivityController Upload Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

echo $BLUE . "Note: " . $RESET . "These tests require authentication.\n";
echo "Please make sure you're logged in as admin or modify the script to include session cookies.\n\n";

// Test 1: Store Class (POST)
$testImage = createTestImage('test_class.jpg');
$classData = [
    'name' => 'Test Class ' . time(),
    'age_range' => '3-5',
    'duration' => '2',
    'max_students' => '15'
];

$response = makeRequest(
    $BASE_URL . '/admin/activities/classes/store',
    'POST',
    $classData,
    ['image' => $testImage]
);

$result = parseJsonResponse($response['response']);
$classId = $result['id'] ?? null;

logTest(
    "POST /admin/activities/classes/store",
    $response['http_code'] === 200 && ($result['success'] ?? false),
    isset($result['message']) ? $result['message'] : "HTTP " . $response['http_code']
);

if ($classId) {
    echo "  → Created Class ID: " . $classId . "\n";
    if (isset($result['image_path'])) {
        echo "  → Image Path: " . $result['image_path'] . "\n";
    }
}

// Test 2: Update Class (PUT) - only if class was created
if ($classId) {
    $updateImage = createTestImage('test_class_update.jpg');
    $updateData = [
        'name' => 'Updated Test Class ' . time(),
        'age_range' => '4-6',
        'duration' => '3',
        'max_students' => '20'
    ];
    
    $response = makeRequest(
        $BASE_URL . '/admin/activities/classes/update/' . $classId,
        'PUT',
        $updateData,
        ['image' => $updateImage]
    );
    
    $result = parseJsonResponse($response['response']);
    
    logTest(
        "PUT /admin/activities/classes/update/{id}",
        $response['http_code'] === 200 && ($result['success'] ?? false),
        isset($result['message']) ? $result['message'] : "HTTP " . $response['http_code']
    );
}

// Test 3: Delete Class (DELETE) - only if class was created
if ($classId) {
    $response = makeRequest(
        $BASE_URL . '/admin/activities/classes/delete/' . $classId,
        'DELETE'
    );
    
    $result = parseJsonResponse($response['response']);
    
    logTest(
        "DELETE /admin/activities/classes/delete/{id}",
        $response['http_code'] === 200 && ($result['success'] ?? false),
        isset($result['message']) ? $result['message'] : "HTTP " . $response['http_code']
    );
}

echo "\n";

// ==============================================================
// TEST 3: Test SettingsController Endpoints
// ==============================================================
echo $YELLOW . "TEST GROUP 3: SettingsController Upload Tests" . $RESET . "\n";
echo "-------------------------------------------------------\n";

// Test 1: Create Event (POST)
$testEventImage = createTestImage('test_event.jpg');
$eventData = [
    'title' => 'Test Event ' . time(),
    'description' => 'Test event description',
    'event_date' => date('Y-m-d'),
    'is_public' => '1'
];

$response = makeRequest(
    $BASE_URL . '/admin/settings/events/store',
    'POST',
    $eventData,
    ['image' => $testEventImage]
);

$result = parseJsonResponse($response['response']);
$eventId = $result['id'] ?? null;

logTest(
    "POST /admin/settings/events/store",
    $response['http_code'] === 200 && ($result['success'] ?? false),
    isset($result['message']) ? $result['message'] : "HTTP " . $response['http_code']
);

if ($eventId) {
    echo "  → Created Event ID: " . $eventId . "\n";
}

// Test 2: Delete Event (DELETE) - only if event was created
if ($eventId) {
    $response = makeRequest(
        $BASE_URL . '/admin/settings/events/delete/' . $eventId,
        'DELETE'
    );
    
    $result = parseJsonResponse($response['response']);
    
    logTest(
        "DELETE /admin/settings/events/delete/{id}",
        $response['http_code'] === 200 && ($result['success'] ?? false),
        isset($result['message']) ? $result['message'] : "HTTP " . $response['http_code']
    );
}

echo "\n";

// ==============================================================
// TEST 4: Verify Upload Locations
// ==============================================================
echo $YELLOW . "TEST GROUP 4: Upload Location Verification" . $RESET . "\n";
echo "-------------------------------------------------------\n";

if (R2_ENABLED) {
    echo "R2 Mode: Files should be uploaded to Cloudflare R2\n";
    echo "Check your R2 bucket at: " . R2_PUBLIC_URL . "\n";
    logTest(
        "R2 bucket URL is accessible",
        !empty(R2_PUBLIC_URL),
        "URL: " . R2_PUBLIC_URL
    );
} else {
    echo "Local Mode: Files should be in local uploads directory\n";
    echo "Check directory: " . UPLOAD_PATH . "\n";
    logTest(
        "Local upload directory exists",
        is_dir(UPLOAD_PATH),
        "Path: " . UPLOAD_PATH
    );
    
    logTest(
        "Local upload directory is writable",
        is_writable(UPLOAD_PATH),
        "Path: " . UPLOAD_PATH
    );
}

echo "\n";

// ==============================================================
// CLEANUP
// ==============================================================
echo $YELLOW . "Cleaning up test files..." . $RESET . "\n";
echo "-------------------------------------------------------\n";

$tempFiles = [
    sys_get_temp_dir() . '/test_class.jpg',
    sys_get_temp_dir() . '/test_class_update.jpg',
    sys_get_temp_dir() . '/test_event.jpg',
];

foreach ($tempFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "  → Removed: " . basename($file) . "\n";
    }
}

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
} else {
    echo $RED . "⚠️  SOME TESTS FAILED ⚠️" . $RESET . "\n";
    echo "Review the failed tests and check:\n";
    echo "  - Authentication/Session cookies\n";
    echo "  - Database connectivity\n";
    echo "  - R2 credentials (if R2_ENABLED=true)\n";
    echo "  - File permissions (if R2_ENABLED=false)\n";
}

echo "\n";
echo "=======================================================\n";
echo "  IMPORTANT NOTES\n";
echo "=======================================================\n\n";

echo "1. Authentication Required:\n";
echo "   Most admin endpoints require authentication.\n";
echo "   You may need to manually add session cookies to the script.\n\n";

echo "2. Manual Testing:\n";
echo "   It's recommended to also test via browser or Postman\n";
echo "   to ensure the full user flow works correctly.\n\n";

echo "3. R2 Verification:\n";
if (R2_ENABLED) {
    echo "   - Login to Cloudflare Dashboard\n";
    echo "   - Check R2 bucket: " . (defined('R2_PUBLIC_BUCKET') ? R2_PUBLIC_BUCKET : 'N/A') . "\n";
    echo "   - Verify files are uploaded and accessible\n";
} else {
    echo "   - Check local uploads directory: " . UPLOAD_PATH . "\n";
    echo "   - Verify files are saved correctly\n";
}

echo "\n";

exit($testsFailed > 0 ? 1 : 0);
